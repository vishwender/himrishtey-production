<h1>New contact inquiry #{{ $inquiry->id }}</h1>
<p><strong>Site:</strong> {{ $inquiry->site_key ?: config('app.name') }}</p>
<p><strong>Name:</strong> {{ $inquiry->name }}</p>
<p><strong>Email:</strong> {{ $inquiry->email }}</p>
<p><strong>Phone:</strong> {{ $inquiry->phone ?: 'Not provided' }}</p>
<p><strong>Profile ID:</strong> {{ $inquiry->profile_id ?: 'Not provided' }}</p>
<p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
<p style="white-space: pre-wrap">{{ $inquiry->message }}</p>
<p>Reply to this email to respond to the visitor.</p>
