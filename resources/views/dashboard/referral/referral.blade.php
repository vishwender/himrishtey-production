@extends('layouts.dashboard')

@section('title', 'Refer & Earn')

@section('content')
<main class="coming-main">

    <section class="coming-section">
        <div class="coming-card">

            <div class="coming-icon">
                🚧
            </div>

            <h1 class="coming-title">Coming Soon</h1>

            <p class="coming-text">
                We're working on something exciting! This feature will be available very soon.
                Thank you for your patience and continued support.
            </p>

            <a href="{{ route('home') }}" class="coming-btn">
                Back to Home
            </a>

        </div>
    </section>

</main>

<style>
    .coming-main {
        min-height: calc(100vh - 180px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background: #fafbff;
    }

    .coming-section {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .coming-card {
        max-width: 600px;
        width: 100%;
        background: #fff;
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        border: 1px solid #f2f2f2;
    }

    .coming-icon {
        width: 90px;
        height: 90px;
        margin: 0 auto 25px;
        background: #e91e63;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
    }

    .coming-title {
        font-size: 42px;
        font-weight: 700;
        color: #222;
        margin-bottom: 15px;
    }

    .coming-text {
        font-size: 17px;
        color: #666;
        line-height: 1.8;
        margin-bottom: 35px;
    }

    .coming-btn {
        display: inline-block;
        padding: 14px 35px;
        background: #e91e63;
        color: #fff;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: .3s;
    }

    .coming-btn:hover {
        background: #c2185b;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width:768px) {

        .coming-main {
            padding: 40px 15px;
        }

        .coming-card {
            padding: 40px 25px;
        }

        .coming-title {
            font-size: 32px;
        }

        .coming-text {
            font-size: 15px;
        }

        .coming-icon {
            width: 75px;
            height: 75px;
            font-size: 34px;
        }
    }
</style>
@endsection