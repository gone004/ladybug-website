@extends('student.layout.production')
@section('head')
    <link rel="stylesheet" href="{{asset('template/pages/student/dashboard/index.min.css')}}?v=4">
    <link rel="stylesheet" href="{{asset('template/libs/card/courses_loren.min.css')}}?v=1">
    <link rel="stylesheet" href="{{asset('template/libs/ratings/index.min.css')}}?v=2"/>

    <link rel="stylesheet" href="{{asset('template/pages/home/courses/index.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/libs/ratings/reviewModal.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/libs/card/OwlCarousel2.min.css')}}">

    <link rel="stylesheet" href="{{asset('template/pages/student/events/calendar.min.css')}}?v=4">

    @if(!session()->has('adminId')&&isset($attendanceNeedReview)&&!$attendanceNeedReview->review)
        <meta name="review-route" content="{{route('student.events.feedback',['student_attendance'=>$attendanceNeedReview->id])}}">
    @endif
    {{--    <link rel="stylesheet" href="{{asset('template/pages/student/dashboard/newIndex.min.css')}}">--}}
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #2a2a2a;
            --text-secondary: #7c8696;
            --card-bg: #ffffff;
            --card-shadow: 0 16px 36px rgba(30, 30, 60, 0.12), 0 2px 8px rgba(30, 30, 60, 0.08);
            --border-color: #e9ecff;
            --accent-blue: #4a7cf7;
            --accent-gold: #f3c32b;
            --accent-purple: #6f6fff;
            --accent-green: #32b44a;
            --accent-red: #b43a36;
            --accent-orange: #ffb36b;
        }

        [data-theme="dark"] {
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --card-bg: #2d2d2d;
            --card-shadow: 0 16px 36px rgba(0, 0, 0, 0.3), 0 2px 8px rgba(0, 0, 0, 0.2);
            --border-color: #404040;
            --accent-blue: #5a8cf8;
            --accent-gold: #f4c430;
            --accent-purple: #7f7fff;
            --accent-green: #42c55a;
            --accent-red: #c44a46;
            --accent-orange: #ffc47b;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: 8px 16px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .theme-toggle-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
        }

        /* scoped to this card */
        #cefr-card-1 {
            border: 0;
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
        }

        #cefr-card-1 .cefr-title {
            font-size: clamp(22px, 3.2vw, 36px);
            color: var(--text-primary);
        }

        #cefr-card-1 .cefr-timeline-wrap {
            padding-bottom: 86px;
            /* room for indicator */
        }

        #cefr-card-1 .cefr-fluent-label {
            position: absolute;
            right: 36px;
            top: 0;
            font-weight: 700;
            color: var(--accent-blue);
        }

        /* Track */
        #cefr-card-1 .cefr-timeline {
            gap: 9px;
            min-height: 92px;
            padding: 8px 2px 0;
            position: relative;
            justify-content: center;
        }

        /* Nodes */
        #cefr-card-1 .cefr-node {
            width: 45px;
            height: 45px;
            min-width: 45px;
            border-radius: 50%;
            background: #efefef;
            border: 5px solid #8c8c8c;
            display: grid;
            place-items: center;
            font-weight: 700;
            color: #2b2b2b;
            user-select: none;
        }

        [data-theme="dark"] #cefr-card-1 .cefr-node {
            background: #404040;
            color: var(--text-primary);
        }

        #cefr-card-1 .cefr-node.blue {
            border-color: var(--accent-blue);
            box-shadow: inset 0 0 0 4px rgba(74,124,247,.12);
        }

        #cefr-card-1 .cefr-node.gold {
            border-color: var(--accent-gold);
            box-shadow: inset 0 0 0 4px rgba(243,195,43,.14);
        }

        #cefr-card-1 .cefr-node.muted {
            opacity: .98;
        }

        /* Connectors (dotted) */
        #cefr-card-1 .cefr-conn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
        }

        #cefr-card-1 .cefr-conn .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #bdbdbd;
        }

        #cefr-card-1 .cefr-conn.blue-to-gold .dot {
            background: var(--accent-blue);
        }
        .dot.blue{
            background: var(--accent-blue) !important;
        }
        .dot.gold{
            background: var(--accent-gold) !important;
        }
        #cefr-card-1 .cefr-conn.blue-to-gold .dot.last {
            background: var(--accent-gold);
            transform: scale(1.1);
        }

        /* Current indicator (chevrons + pill) */
        #cefr-card-1 .cefr-current-indicator {
            position: absolute;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
        }

        #cefr-card-1 .chevrons {
            width: 36px;
            height: 36px;
            margin-bottom: 6px;
        }

        #cefr-card-1 .pill {
            background: var(--accent-purple);
            color: #fff;
            border-radius: 16px;
            padding: 8px 16px;
            font-weight: 700;
            box-shadow: 0 8px 18px rgba(114,119,255,.35);
            white-space: nowrap;
        }
        
        @media (max-width: 576px) {
            #cefr-card-1 .cefr-timeline{
                gap: 3px;
            }
            #cefr-card-1 .cefr-node {
                width: 30px;
                height: 30px;
                min-width: 30px;
                border-width: 3px;
                font-size: 0.8rem;
            }
            #cefr-card-1 .cefr-conn{
                gap: 2px;
            }
            #cefr-card-1 .cefr-conn .dot {
                width: 6px;
                height: 6px;
            }
        }

        .has-level-tip{
            position: relative;
        }
        .level-tip{
            position: absolute;
            width: max-content;
            left: 100%;
            bottom: calc(100% + 16px);
            transform: translateX(-50%) scale(.98);
            background: var(--accent-blue);
            color: #fff;
            border-radius: 24px;
            padding: 14px 18px 16px;
            box-shadow: 0 14px 30px rgba(43,66,160,.25);
            opacity: 0;
            visibility: hidden;
            transition: opacity .18s ease, transform .18s ease;
            z-index: 10000;
            pointer-events: none;
        }
        .level-tip::after{
            content: "";
            position: absolute;
            left: 46%;
            transform: translateX(-50%) rotate(45deg);
            bottom: -11px;
            width: 22px;
            height: 22px;
            background: var(--accent-blue);
            border-radius: 3px;
            box-shadow: 6px 6px 18px rgba(43,66,160,.18);
        }
        .has-level-tip:hover .level-tip, .has-level-tip:focus-within .level-tip{
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1);
        }
        .level-tip p{
            margin: 0;
            padding: 0;
        }

        .card{
            border: 0;
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
            color: var(--text-primary);
        }
        
        .upcoming-card .card-body{
            padding: 1.5rem;
            max-height: 500px;
            overflow: auto;
        }
        
        .session{
            background: #eff5f9;
            border-radius: 18px;
            padding: 1rem 1.25rem;
            position: relative;
        }
        
        [data-theme="dark"] .session {
            background: #3a4a5c;
        }
        
        .session + .session{
            margin-top: 1rem;
        }
        
        .session-title{
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: .2rem;
        }
        
        .session-time{
            color: var(--text-secondary);
            font-size: .95rem;
        }

        .session .rounded-circle img {
            position: absolute;
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            top: -20px;
            right: -10px;
        }
        
        .btn-join{
            color: #fff;
            border: 0;
            border-radius: 999px;
            padding: .55rem 1.1rem;
            background-image: linear-gradient(135deg, var(--accent-purple), #5a63ff);
            box-shadow: 0 8px 18px rgba(90, 99, 255, .35);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .btn-join:hover{ 
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(90, 99, 255, .4);
        }
        .btn-join:active{
            transform: translateY(0);
        }

        /* Card look */
        .payment-card{
            border: 0;
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
        }
        .payment-card ul{
            max-height: 260px;
            overflow: auto;
        }

        .hist{
            background: #eef4f6;
            border-radius: 18px;
            padding: 14px 16px;
        }
        
        [data-theme="dark"] .hist {
            background: #3a4a5c;
        }
        
        .hist + .hist{ margin-top: 12px; }

        /* Next payment pill */
        .next-pill{
            color: #fff;
            font-weight: 600;
            padding: .7rem 1rem;
            background-image: linear-gradient(135deg, var(--accent-purple), #5a63ff);
            box-shadow: 0 8px 18px rgba(90,99,255,.25);
        }

        /* Update button (green pill) */
        .update-btn{
            color: #fff;
            border: 0;
            padding: .72rem 1rem;
            background-image: linear-gradient(135deg, var(--accent-green), #00b05b);
        }

        .att-card{
            border: 0;
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
        }

        /* Squares */
        .dots{ display:flex; flex-wrap:wrap; gap:12px; }
        .dot{
            width:18px; height:18px;
            border-radius:6px;
        }
        .dot.green{ background: var(--accent-green); }
        .dot.red{ background: var(--accent-red); }
        .dot.blue{ background: var(--accent-blue); }
        .dot.gray{ background: #585b67; }

        /* Legend */
        .legend li + li{ margin-top:.25rem; }
        .count{ margin-right:.35rem; color: var(--text-secondary); }
        .text-green{ color: var(--accent-green); }
        .text-blue{ color: var(--accent-blue); }
        .text-red{ color: var(--accent-red); }

        .cert-card{
            border: 0;
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
        }

        /* Medal icon */
        .cert-medal{ width: 42px; height: 42px; border-radius: 50%; }

        /* Pill buttons */
        .pill-btn{
            color: #fff;
            border: 0;
            border-radius: 999px;
            padding: .6rem 1.1rem;
            background-image: linear-gradient(135deg, var(--accent-purple), #5a63ff);
        }

        /* Card */
        .points-card{
            border: 0;
            border-radius: 22px;
            box-shadow: var(--card-shadow);
            background: var(--card-bg);
        }

        /* Big pill with total points */
        .points-pill{
            background: #f1f5f6;
            border: 1px solid #e6eaec;
            border-radius: 999px;
            padding: 1.1rem 2rem;
            min-width: 320px;
            font-size: clamp(1.5rem, 3.8vw, 2.25rem);
            color: var(--text-primary);
            box-shadow: 0 10px 20px rgba(0,0,0,.08);
        }
        
        [data-theme="dark"] .points-pill {
            background: #404040;
            border-color: #555;
        }

        /* Redeem button (orange pill) */
        .redeem-btn{
            color: #fff;
            border: 0;
            padding: .7rem 1.2rem;
            background-image: linear-gradient(135deg, var(--accent-orange), #ffa24f);
            box-shadow: 0 8px 18px rgba(255,162,79,.35);
        }

        /* Right-side link */
        .points-link{
            color: #3b4f9d;
            font-weight: 600;
            text-decoration: none;
        }
        .points-link:hover{ text-decoration: underline; }

        .month-title{
            font-weight:700;
            color: #2f3a8f;
        }
        
        [data-theme="dark"] .month-title {
            color: var(--accent-blue);
        }

        .month-nav{
            border:2px solid var(--border-color);
            border-radius:14px;
            padding:.55rem .8rem;
        }
        .month-title .highlight{
            color:var(--primary);
        }

        .btn-nav{
            width:44px;
            height:44px;
            border-radius:50%;
            border:2px solid #2f3a8f;
            background:#f7f9ff;
            color:var(--primary);
            display:grid;
            place-items:center;
        }
        
        [data-theme="dark"] .btn-nav {
            border-color: var(--accent-blue);
            background: #2a3a5f;
            color: var(--accent-blue);
        }
        
        .btn-nav iconify-icon{font-size:1.15rem;}

        /* Responsive Design */
        @media (max-width: 1200px) {
            .container-fluid {
                padding: 0 1rem;
            }
        }

        @media (max-width: 992px) {
            .col-lg-6 {
                margin-bottom: 1.5rem;
            }
            
            .col-lg-3 {
                margin-bottom: 1.5rem;
            }
            
            .card {
                margin-bottom: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .theme-toggle {
                top: 15px;
                right: 15px;
                padding: 6px 12px;
            }
            
            .theme-toggle-btn {
                font-size: 1rem;
            }
            
            .row {
                margin: 0;
            }
            
            .col-12, .col-md-6, .col-lg-3, .col-lg-6 {
                padding: 0 0.5rem;
                margin-bottom: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .session {
                padding: 0.75rem 1rem;
            }
            
            .btn-join, .pill-btn, .redeem-btn, .update-btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .points-pill {
                min-width: 280px;
                padding: 0.8rem 1.5rem;
                font-size: clamp(1.2rem, 4vw, 1.8rem);
            }
        }

        @media (max-width: 576px) {
            .theme-toggle {
                top: 10px;
                right: 10px;
                padding: 4px 8px;
            }
            
            .row {
                margin: 0;
            }
            
            .col-12, .col-md-6, .col-lg-3, .col-lg-6 {
                padding: 0 0.25rem;
                margin-bottom: 0.75rem;
            }
            
            .card {
                margin-bottom: 0.75rem;
                border-radius: 16px;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .session {
                padding: 0.5rem 0.75rem;
                border-radius: 12px;
            }
            
            .session-title {
                font-size: 0.9rem;
            }
            
            .session-time {
                font-size: 0.8rem;
            }
            
            .btn-join, .pill-btn, .redeem-btn, .update-btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
            
            .points-pill {
                min-width: 240px;
                padding: 0.6rem 1rem;
                font-size: clamp(1rem, 5vw, 1.5rem);
            }
            
            .month-nav {
                padding: 0.4rem 0.6rem;
            }
            
            .btn-nav {
                width: 36px;
                height: 36px;
            }
            
            .btn-nav iconify-icon {
                font-size: 1rem;
            }
            
            .dots {
                gap: 8px;
            }
            
            .dot {
                width: 14px;
                height: 14px;
            }
        }

        @media (max-width: 400px) {
            .points-pill {
                min-width: 200px;
                padding: 0.5rem 0.8rem;
            }
            
            .session {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .btn-join, .pill-btn, .redeem-btn, .update-btn {
                align-self: flex-end;
            }
        }
    </style>

@endsection
@section('script')

    <script src="{{asset('template/libs/card/OwlCarousel2.min.js')}}"></script>
    <script src="{{asset('template/pages/student/dashboard/index.min.js')}}"></script>
    <script src="{{asset('template/libs/ratings/reviewModal.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            const csrfToken= document.head.querySelector('meta[name=csrf_token]').content;

            const reviewRoute=document.querySelector('meta[name="review-route"]')?.getAttribute('content');
            if(reviewRoute){
                $('#review_modal')?.modal('show');
                $('#review_form').attr('action',reviewRoute);
            }
            const reviewPlus=document.querySelector('meta[name="review-plus"]')?.getAttribute('content');
            if(reviewPlus){
                $('#review_plus_modal')?.modal('show');
            }
            $("#review_plus_button").on('click',function (){
                $('#review_plus_modal')?.modal('hide');
            })

            // Dark/Light mode toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            
            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            body.setAttribute('data-theme', currentTheme);
            
            // Update toggle button icon
            updateThemeIcon(currentTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = body.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                body.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                const icon = themeToggle.querySelector('i');
                if (theme === 'dark') {
                    icon.className = 'fas fa-sun';
                } else {
                    icon.className = 'fas fa-moon';
                }
            }

            const container = document.getElementById('cefr-timeline');
            const currentDot = container.querySelector('.dot.current.gold');
            const indicator = document.getElementById('cefr-current-indicator');

            if (container && currentDot) {
                const containerRect = container.getBoundingClientRect();
                const dotRect = currentDot.getBoundingClientRect();

                // position inside the container
                const leftInside = dotRect.left - containerRect.left -60;

                indicator.style.position = 'absolute';
                indicator.style.left = `${leftInside}px`;
            }

            $('body').on('click','#attendanceCard .btn-nav',function (){
                const current=$(this).data('current');
                const action=$(this).data('action')
                $.ajax({
                    type: "POST",
                    url: `/student/attendance/${current}/${action}`,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        $("#attendanceCard").html(response)
                    }
                })
            })
        });
    </script>

@endsection
@section('content')
    {{ App::setLocale(Auth::user()->lang) }}
    
    <!-- Dark/Light Mode Toggle Button -->
    <div class="theme-toggle">
        <button class="theme-toggle-btn" id="themeToggle" aria-label="Toggle dark mode">
            <i class="fas fa-moon"></i>
        </button>
    </div>
    
    <div class="container-fluid">
        <div class="row mx-1">
            <div class="col-12 col-lg-6" dir="ltr">
                <div class="card h-48 cefr-card shadow-sm mb-4" id="cefr-card-1">
                    <div class="card-body">
                        <h3 class="fw-bold cefr-title mb-4 card-header">Know more about your level</h3>
                        <div class="cefr-timeline-wrap position-relative">
                            <span class="cefr-fluent-label">Fluent</span>

                            <div class="cefr-timeline d-flex align-items-center" id="cefr-timeline">
                                <!-- A0 -->
                                <div class="cefr-node {{in_array("A0",$passedLevels)?'blue':''}} {{"A0"==$student->level?' gold current':''}} has-level-tip" data-level="A0">
                                    A0
                                    <div class="level-tip ">
                                        <div class="tip-title">The Social Speaker</div>
                                        <p>You can talk about family, hobbies, work, and dreams.</p>
                                        <p>You can join global online groups and discussions.</p>
                                        <p>You can start watching English content without subtitles.</p>
                                    </div>
                                </div>

                                <!-- A1 -->
                                @foreach(['A1','A2','B1','B2','C1','C2'] as $level)
                                    <div class="cefr-node {{in_array($level,$passedLevels)?'blue':''}} {{$level==$currentLevel?' gold current':''}}  has-level-tip" data-level="{{$level}}">
                                        {{$level}}
                                        <div class="level-tip ">
                                            <div class="tip-title">The Social Speaker</div>
                                            <p>You can talk about family, hobbies, work, and dreams.</p>
                                            <p>You can join global online groups and discussions.</p>
                                            <p>You can start watching English content without subtitles.</p>
                                        </div>
                                    </div>
                                    @if($level!='C2')
                                        <div class="cefr-conn {{$level=='C1'?'to-fluent':''}}">
                                                <?php
                                                $currentSubLevelKey=$student->sub_level=='Beginner'?0:($student->sub_level=='Intermediate'?1:2);
                                                ?>
                                            @foreach(['Beginner','Intermediate','Advanced'] as $key => $subLevel)
                                                @if($level==$currentLevel)
                                                    <span class="dot {{$subLevel==$student->sub_level?'current':''}} {{$currentSubLevelKey>=$key?'gold':''}}" ></span>
                                                @else
                                                    <span class="dot {{in_array($level,$passedLevels)?'blue':''}}" ></span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Floating indicator -->
                            <div class="cefr-current-indicator" id="cefr-current-indicator" aria-hidden="true">
                                <svg class="chevrons" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 19L24 12L33 19" stroke="#b2832a" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 29L24 22L33 29" stroke="#b2832a" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M24 30V38" stroke="#b2832a" stroke-width="8" stroke-linecap="round"/>
                                </svg>
                                <div class="pill">You are here</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card h-48 points-card bg-white mb-4" >
                    <div class="card-body">
                        <h4 class="mb-4">Your Points</h4>
                        <div class="d-flex justify-content-center mb-4">
                            <div class="points-pill text-center fw-semibold">400 Points</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <button class="btn redeem-btn rounded-pill px-4 fw-semibold">Redeem</button>
                            <a href="#" class="points-link">Learn more about your points</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 upcoming-card bg-white">
                    <h3 class="fw-bold mb-4 card-header">Upcoming class</h3>
                    <div class="card-body" >
                        @foreach($events as $event)
                            <div class="session d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="session-title">{{getDaysOfWeek()[($event->day_of_week+2)]["day"]}}</div>
                                    <div class="session-time">{{Carbon\Carbon::now()->startOfWeek()->addDays($event->day_of_week - 1)->addWeeks($event->day_of_week<\Carbon\Carbon::now()->dayOfWeek?1:0)->setTimeFromTimeString($event->start_time)->translatedFormat('j F g:i A')}}</div>
                                </div>
                                <a href="{{route('student.meeting.show',['path'=>$event->path])}}" target="_blank" class="btn btn-join">{{__('Join')}}</a>
                            </div>
                        @endforeach
                        <div class="session d-flex align-items-center justify-content-between" style="background: #ff3e1d4a">
                            <div>
                                <div class="session-title">{{__('events.PublicLounge')}}</div>
                            </div>
                            <a href="{{route('student.rooms',['type'=>'public'])}}" target="_blank" class="btn btn-join">{{__('Join')}}</a>
                        </div>
                        <div class="session d-flex align-items-center justify-content-between" style="background: #ff3e1d4a">
                            <div>
                                <div class="session-title">
                                    @if(auth()->user()->gender=="female")
                                        {{__("events.LadiesLounge")}}
                                    @else
                                        {{__("events.MensLounge")}}
                                    @endif
                                </div>
                            </div>
                            <a href="{{route('student.rooms',['type'=>'private'])}}" target="_blank" class="btn btn-join">{{__('Join')}}</a>
                        </div>
                        <div class="session d-flex align-items-center justify-content-between" style="background: #f5f9ed">
                            <div>
                                <div class="session-title">
                                    {{__('events.MeetYourAdviser')}}
                                </div>
                            </div>
                            <a href="{{route('guest.meetings',['path'=>generateMeetingLink(auth()->user()->student->id)])}}"
                               target="_blank" class="btn btn-join">{{__('Join')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card h-100 payment-card bg-white">
                    <div class="mb-4 card-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold mb-0">Payment History</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-4">
                            @foreach($payments as $payment)
                                <li class="hist d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold lh-1 mb-1">{{\Carbon\Carbon::parse($payment->created_at)->translatedFormat('j.F Y')}} </div>
                                        <small class="text-secondary">{{$student->pm_type??$payment->platform}}</small>
                                    </div>
                                    <div class="fw-semibold">{{$payment->subtotal}}$</div>
                                </li>
                            @endforeach
                        </ul>
                        @if(count($stripeSubscriptions) || count($paypalSubscriptions))
                            <div class="text-center mb-3">
                                <span class="badge next-pill rounded-pill w-100">Your next payment</span>
                            </div>
                            @foreach($stripeSubscriptions as $stripeSubscription)
                                    <?php
                                    $paused=\App\Models\StudentCourse::where('course_id',$stripeSubscription->course->id)->where('student_id',$student->id)->whereHas('studentCoursePauses',
                                        function ($pause)use($stripeSubscription){
                                            return $pause->whereNull('resumes_at')->orWhere('resumes_at','>',\Carbon\Carbon::parse($stripeSubscription->latestPayment->created_at)->addMonth());
                                        })->exists();
                                    ?>
                                @if(!$paused)
                                    <div class="text-center mb-3">
                                        <div class="fs-5 fw-semibold text-center">{{\Carbon\Carbon::parse($stripeSubscription->latestPayment->created_at)->addMonth()->translatedFormat('j.F Y')}} </div>
                                        <div class="text-secondary text-center">{{$stripeSubscription->latestPayment->subtotal}}$</div>
                                    </div>
                                @endif
                            @endforeach
                            @foreach($paypalSubscriptions as $paypalSubscription)
                                    <?php
                                    $paused=\App\Models\StudentCourse::where('course_id',$paypalSubscription->course->id)->where('student_id',$student->id)->whereHas('studentCoursePauses',
                                        function ($pause)use($paypalSubscription){
                                            return $pause->whereNull('resumes_at')->orWhere('resumes_at','>',\Carbon\Carbon::parse($paypalSubscription->latestPayment->created_at)->addMonth());
                                        })->exists();
                                    ?>
                                @if(!$paused)
                                    <div class="text-center mb-3">
                                        <div class="fs-5 fw-semibold text-center">{{\Carbon\Carbon::parse($paypalSubscription->latestPayment->created_at)->addMonth()->translatedFormat('j.F Y')}} </div>
                                        <div class="text-secondary text-center">{{$paypalSubscription->latestPayment->subtotal}}$</div>
                                    </div>
                                @endif

                            @endforeach

                        @endif

                        <a href="{{route('student.profile')}}#demo_payement" target="_blank" class="btn update-btn w-100 rounded-pill fw-semibold shadow-sm">
                            Update your payment method
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-1">
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card bg-white">

                    <h3 class="fw-bold mb-4 card-header">Upcoming workshops</h3>
                    <div class="card-body">
                        @foreach($workshops as $event)
                            <div class="session d-flex align-items-center justify-content-between">
                                <div class="rounded-circle ">
                                    <img class="img-fluid " src="{{--{{$event->tutor->user->getFirstMediaUrl('avatars','thumb')}}--}}https://remtoo.net/img/booking/reviews3.webp" alt="User avatar">
                                </div>
                                <div>
                                    <div class="session-title">{{ $event->title }}</div>
                                    <div class="session-title"></div>
                                    <div class="session-time">{{ \Carbon\Carbon::parse($event->start_date)->dayName }} - {{Carbon\Carbon::now()->startOfWeek()->addDays($event->day_of_week - 1)->addWeeks($event->day_of_week<\Carbon\Carbon::now()->dayOfWeek?1:0)->setTimeFromTimeString($event->start_time)->translatedFormat('j F g:i A')}}</div>
                                </div>
                                <a href="{{route('student.meeting.show',['path'=>$event->path])}}" target="_blank" class="btn btn-join">{{__('Join')}}</a>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card att-card bg-white"  id="attendanceCard">
                    <div class="mb-4 card-header">
                        <div class="month-nav d-flex align-items-center justify-content-between mb-3 ">
                            <button class="btn btn-nav" data-action="subMonth" data-current="{{\Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
                                <iconify-icon icon="mdi:arrow-left-bold"></iconify-icon>
                            </button>
                            <div class="fs-5 month-title">{{\Carbon\Carbon::now()->translatedFormat('Y F')}}</div>
                            <button class="btn btn-nav" data-action="addMonth" data-current="{{\Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}">
                                <iconify-icon icon="mdi:arrow-right-bold"></iconify-icon>
                            </button>
                        </div>
                        <h3 class="fw-bold ">Attendance</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <!-- Squares -->
                            <div class="flex-grow-1 me-4">
                                <div class="dots mb-3">
                                    @foreach($attendances as $attendance)
                                        <span class="dot {{is_null($attendance->joined_at)?'red':'green'}}"></span>
                                    @endforeach

                                </div>

                            </div>
                            <!-- Legend -->
                            <ul class="list-unstyled mb-0 small legend">
                                <li><span class="count">{{$attendances->whereNotNull('joined_at')->count()}}</span> <span class="text-green">attendance</span></li>
                                <li><span class="count">{{$postponed}}</span> <span class="text-blue">Postponed</span></li>
                                <li><span class="count">{{$attendances->whereNull('joined_at')->count()}}</span> <span class="text-red">absence</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card cert-card bg-white">
                    <h3 class="fw-bold mb-4 card-header">Your Certificates</h3>
                    <div class="card-body">
                        @foreach($certificates as $certificate)
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-start">
                                    <svg class="cert-medal me-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" aria-hidden="true">
                                        <circle cx="24" cy="24" r="14" fill="#5ea6ff"/>
                                        <circle cx="24" cy="24" r="9" fill="#ffffff" opacity=".7"/>
                                        <path d="M18 34 12 56l12-8 12 8-6-22" fill="#556cff"/>
                                    </svg>
                                    <div>
                                        <div class="fw-semibold fs-5 mb-1">{{$certificate->course->name}}</div>
                                        <div class="text-secondary">Issued {{\Carbon\Carbon::parse($certificate->created_at)->translatedFormat("F j, Y")}}</div>
                                    </div>
                                </div>
                                <a  href="{{route('downloadCertificate',['test'=>$certificate->quiz_id,'student'=>$student->id])}}"
                                    target="_blank" class="btn pill-btn shadow-sm">Download PDF</a>
                            </div>
                        @endforeach
                        @if($certificateTest)
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-start">
                                    <svg class="cert-medal me-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" aria-hidden="true">
                                        <circle cx="24" cy="24" r="14" fill="#5ea6ff"/>
                                        <circle cx="24" cy="24" r="9" fill="#ffffff" opacity=".7"/>
                                        <path d="M18 34 12 56l12-8 12 8-6-22" fill="#556cff"/>
                                    </svg>
                                    <div class="fw-bold display-6 mb-0 me-3">{{$certificateTest->course->name}}</div>
                                </div>
                                <a target="_blank" href="{{($certificateTest->isRandom)?route('student.test.showRandom',['test'=>$certificateTest->id]):route('student.test.show',['test'=>$certificateTest->id])}}" class="btn pill-btn shadow-sm">Take next level exam</a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100  bg-white">
                    <h4 class="fw-bold mb-4 card-header">Upcoming class</h4>
                    <div class="card-body">
                        @foreach($courses as $course)
                            <div class="session d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="session-title">{{$course->name}}  {{in_array($course->id,array_values(\App\Services\Constantes::NEWMATERIALFORLEVEL))? '( new )':''}}</div>
                                </div>
                                <a href="{{ route('student.course.show', ['course' => $course->id]) }}" target="_blank" class="btn btn-join">{{__('buttons.Continue')}}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection