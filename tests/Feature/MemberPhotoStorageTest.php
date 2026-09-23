<?php

namespace Tests\Feature;

use App\Jobs\ProcessMemberPhoto;
use App\Services\MemberPhotoService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberPhotoStorageTest extends TestCase
{
    public function test_member_upload_uses_photo_disk_and_preserves_legacy_urls(): void
    {
        $this->assertSame(public_path('photos/photo'), config('filesystems.disks.profile_photos.root'));
        Storage::fake('profile_photos', ['url' => config('filesystems.disks.profile_photos.url')]);
        Storage::fake('public');
        Bus::fake();
        config(['database.connections.site' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        Schema::connection('site')->create('member_photos', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('photo');
            $table->string('photo_approved');
            $table->integer('photo_privacy');
        });
        Schema::connection('site')->create('members', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
        });
        DB::connection('site')->table('members')->insert(['id' => 7]);
        $service = app(MemberPhotoService::class);
        $photo = $service->upload(7, UploadedFile::fake()->create('portrait.jpg', 10, 'image/jpeg'));
        $this->assertSame(basename($photo->photo), $photo->photo);
        $this->assertMatchesRegularExpression('/^member-7-[0-9]+\.jpg$/', $photo->photo);
        $secondPhoto = $service->upload(7, UploadedFile::fake()->create('portrait.jpg', 10, 'image/jpeg'), true);
        $this->assertNotSame($photo->photo, $secondPhoto->photo);
        $this->assertSame($secondPhoto->photo, DB::connection('site')->table('members')->where('id', 7)->value('photo'));
        Storage::disk('profile_photos')->assertExists($secondPhoto->photo);
        Storage::disk('profile_photos')->assertExists($photo->photo);
        Storage::disk('public')->assertMissing($photo->photo);
        $this->assertStringContainsString('/photos/photo/', $service->url($photo->photo));
        $this->assertStringContainsString('/storage/members/7/original/old.jpg', $service->url('members/7/original/old.jpg'));
        Bus::assertDispatched(ProcessMemberPhoto::class, fn ($job) => $job->disk === 'profile_photos' && $job->originalPath === $photo->photo);
        $service->delete(7, $photo->id);
        Storage::disk('profile_photos')->assertMissing($photo->photo);
    }
}
