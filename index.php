<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SCFMS - Smart College Feedback Management System</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f8fc;
            color: #1f2937;
        }

        /* ================= NAVBAR ================= */

        .navbar {
            width: 100%;
            height: 75px;
            background: rgba(255, 255, 255, 0.96);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 7%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        }

        .logo {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            font-weight: bold;
        }

        .logo-text h2 {
            color: #252b48;
            font-size: 20px;
        }

        .logo-text span {
            color: #7b8195;
            font-size: 10px;
            letter-spacing: 1px;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-btn,
        .register-btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .login-btn {
            color: #667eea;
            border: 1px solid #667eea;
            background: white;
        }

        .login-btn:hover {
            background: #f0f2ff;
        }

        .register-btn {
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: 1px solid transparent;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* ================= HERO ================= */

        .hero {
            min-height: 100vh;
            padding: 130px 7% 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 60px;
            background:
                radial-gradient(circle at 90% 20%, #e8eaff 0, transparent 28%),
                radial-gradient(circle at 10% 90%, #eee8ff 0, transparent 30%),
                #f8f9ff;
        }

        .hero-content {
            width: 55%;
        }

        .small-title {
            display: inline-block;
            background: #eceeff;
            color: #667eea;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 54px;
            line-height: 1.12;
            color: #20243a;
            margin-bottom: 20px;
        }

        .hero h1 span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            max-width: 600px;
            font-size: 17px;
            line-height: 1.8;
            color: #697086;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .primary-btn {
            text-decoration: none;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 14px 26px;
            border-radius: 9px;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
        }

        .primary-btn:hover {
            transform: translateY(-3px);
        }

        .secondary-btn {
            text-decoration: none;
            color: #667eea;
            background: white;
            border: 1px solid #dfe2f2;
            padding: 14px 26px;
            border-radius: 9px;
            font-weight: bold;
            transition: 0.3s;
        }

        .secondary-btn:hover {
            background: #f2f3ff;
        }

        /* ================= HERO VISUAL ================= */

        .hero-visual {
            width: 42%;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .dashboard-card {
            width: 390px;
            min-height: 390px;
            background: white;
            border-radius: 25px;
            padding: 25px;
            box-shadow: 0 25px 60px rgba(44, 52, 90, 0.15);
            transform: rotate(2deg);
            position: relative;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .dashboard-header h3 {
            font-size: 18px;
            color: #252b48;
        }

        .status {
            background: #e8f8ef;
            color: #20a05a;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .rating-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 22px;
            border-radius: 18px;
            margin-bottom: 20px;
        }

        .rating-box p {
            font-size: 13px;
            opacity: 0.85;
        }

        .rating-number {
            font-size: 42px;
            font-weight: bold;
            margin-top: 5px;
        }

        .stars {
            font-size: 19px;
            margin-top: 5px;
        }

        .progress-item {
            margin: 17px 0;
        }

        .progress-title {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .progress-bar {
            height: 8px;
            background: #edf0f6;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, #667eea, #8d68c7);
        }

        .fill-90 {
            width: 90%;
        }

        .fill-82 {
            width: 82%;
        }

        .fill-75 {
            width: 75%;
        }

        /* ================= STATS ================= */

        .stats {
            padding: 45px 7%;
            background: white;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            border-bottom: 1px solid #eee;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-card h2 {
            color: #667eea;
            font-size: 30px;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #777f93;
            font-size: 14px;
        }

        /* ================= FEATURES ================= */

        .features {
            padding: 90px 7%;
            text-align: center;
        }

        .section-label {
            color: #667eea;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .features h2 {
            font-size: 35px;
            color: #20243a;
            margin-bottom: 12px;
        }

        .section-description {
            color: #747b8e;
            max-width: 600px;
            margin: 0 auto 45px;
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .feature-card {
            background: white;
            padding: 32px 25px;
            border-radius: 18px;
            text-align: left;
            border: 1px solid #eef0f6;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(42, 48, 80, 0.1);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #eff0ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: #292e45;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #777f93;
            font-size: 14px;
            line-height: 1.7;
        }

        /* ================= HOW IT WORKS ================= */

        .how-section {
            padding: 90px 7%;
            background: #f1f3ff;
            text-align: center;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1000px;
            margin: 50px auto 0;
        }

        .step {
            background: white;
            padding: 30px;
            border-radius: 18px;
            position: relative;
        }

        .step-number {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 18px;
        }

        .step h3 {
            margin-bottom: 10px;
            color: #292e45;
        }

        .step p {
            color: #777f93;
            line-height: 1.6;
            font-size: 14px;
        }

        /* ================= CTA ================= */

        .cta {
            padding: 90px 7%;
            text-align: center;
            background: white;
        }

        .cta-box {
            max-width: 1000px;
            margin: auto;
            padding: 55px 30px;
            border-radius: 25px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 20px 45px rgba(102, 126, 234, 0.25);
        }

        .cta-box h2 {
            font-size: 34px;
            margin-bottom: 15px;
        }

        .cta-box p {
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 25px;
            line-height: 1.7;
        }

        .cta-button {
            display: inline-block;
            text-decoration: none;
            background: white;
            color: #667eea;
            padding: 13px 28px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* ================= FOOTER ================= */

        footer {
            background: #20243a;
            color: white;
            padding: 35px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        footer p {
            color: #aeb3c4;
            font-size: 13px;
        }

        .footer-name {
            font-weight: bold;
            font-size: 18px;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 120px;
            }

            .hero-content,
            .hero-visual {
                width: 100%;
            }

            .hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-buttons {
                justify-content: center;
            }

            .feature-grid,
            .steps {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .dashboard-card {
                width: 360px;
            }

        }

        @media (max-width: 600px) {

            .navbar {
                padding: 0 5%;
            }

            .logo-text {
                display: none;
            }

            .login-btn,
            .register-btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .hero h1 {
                font-size: 38px;
            }

            .hero p {
                font-size: 15px;
            }

            .dashboard-card {
                width: 100%;
                transform: none;
            }

            .features h2,
            .cta-box h2 {
                font-size: 28px;
            }

            footer {
                flex-direction: column;
                text-align: center;
            }

        }

    </style>

</head>

<body>
    

    <!-- ================= NAVBAR ================= -->

    <nav class="navbar">

        <a href="index.php" class="logo">

            <div class="logo-icon">
                S
            </div>

            <div class="logo-text">
                <h2>SCFMS</h2>
                <span>SMART COLLEGE FEEDBACK</span>
            </div>

        </a>

        <div class="nav-buttons">

            <a href="login.php" class="login-btn">
                Login
            </a>

            <a href="register.php" class="register-btn">
                Register
            </a>

        </div>

    </nav>


    <!-- ================= HERO ================= -->

    <section class="hero">

        <div class="hero-content">

            <div class="small-title">
                🎓 SMART COLLEGE FEEDBACK SYSTEM
            </div>

            <h1>
                Your Voice.
                <br>
                <span>Better Education.</span>
            </h1>

            <p>
                SCFMS helps students share meaningful feedback about
                courses and faculty. Your feedback helps colleges
                understand what is working and where education can improve.
            </p>

            <div class="hero-buttons">

                <a href="login.php" class="primary-btn">
                    Give Your Feedback →
                </a>

                <a href="#features" class="secondary-btn">
                    Explore System
                </a>

            </div>

        </div>


        <!-- Dashboard Illustration -->

        <div class="hero-visual">

            <div class="dashboard-card">

                <div class="dashboard-header">

                    <h3>Feedback Overview</h3>

                    <div class="status">
                        ● Active
                    </div>

                </div>


                <div class="rating-box">

                    <p>Overall Satisfaction</p>

                    <div class="rating-number">
                        4.6
                    </div>

                    <div class="stars">
                        ★ ★ ★ ★ ★
                    </div>

                </div>


                <div class="progress-item">

                    <div class="progress-title">
                        <span>Teaching Quality</span>
                        <b>90%</b>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill fill-90"></div>
                    </div>

                </div>


                <div class="progress-item">

                    <div class="progress-title">
                        <span>Course Content</span>
                        <b>82%</b>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill fill-82"></div>
                    </div>

                </div>


                <div class="progress-item">

                    <div class="progress-title">
                        <span>Communication</span>
                        <b>75%</b>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill fill-75"></div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- ================= STATS ================= -->

    <section class="stats">

        <div class="stat-card">

            <h2>100%</h2>

            <p>Digital Feedback</p>

        </div>


        <div class="stat-card">

            <h2>5+</h2>

            <p>Feedback Categories</p>

        </div>


        <div class="stat-card">

            <h2>24/7</h2>

            <p>System Availability</p>

        </div>

    </section>


    <!-- ================= FEATURES ================= -->

    <section class="features" id="features">

        <div class="section-label">
            WHY SCFMS?
        </div>

        <h2>
            Everything You Need
        </h2>

        <p class="section-description">

            A simple and organized platform designed to make
            college feedback easier, faster and more useful.

        </p>


        <div class="feature-grid">


            <div class="feature-card">

                <div class="feature-icon">
                    📝
                </div>

                <h3>
                    Easy Feedback
                </h3>

                <p>
                    Students can quickly rate courses and faculty
                    using a simple and user-friendly feedback form.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    🔒
                </div>

                <h3>
                    Anonymous Option
                </h3>

                <p>
                    Students can choose anonymous feedback when
                    they want to share their honest opinions freely.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    📊
                </div>

                <h3>
                    Smart Reports
                </h3>

                <p>
                    Administrators can view ratings, categories,
                    feedback comments and sentiment analysis.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    👨‍🎓
                </div>

                <h3>
                    Student Friendly
                </h3>

                <p>
                    Students can submit feedback and view their
                    previous feedback from one simple dashboard.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    👨‍🏫
                </div>

                <h3>
                    Faculty Insights
                </h3>

                <p>
                    Faculty members can review feedback related
                    to their assigned courses.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    ⚡
                </div>

                <h3>
                    Faster Management
                </h3>

                <p>
                    Admins can manage users, courses, feedback
                    cycles and reports efficiently.
                </p>

            </div>

        </div>

    </section>


    <!-- ================= HOW IT WORKS ================= -->

    <section class="how-section">

        <div class="section-label">
            HOW IT WORKS
        </div>

        <h2>
            Simple. Fast. Effective.
        </h2>

        <p class="section-description">

            Submit feedback in just a few simple steps.

        </p>


        <div class="steps">


            <div class="step">

                <div class="step-number">
                    01
                </div>

                <h3>
                    Login
                </h3>

                <p>
                    Login to your SCFMS account using your
                    registered credentials.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    02
                </div>

                <h3>
                    Give Feedback
                </h3>

                <p>
                    Select your course and provide ratings
                    and comments about your learning experience.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    03
                </div>

                <h3>
                    Make an Impact
                </h3>

                <p>
                    Your feedback helps faculty and management
                    improve the quality of education.
                </p>

            </div>


        </div>

    </section>


    <!-- ================= CTA ================= -->

    <section class="cta">

        <div class="cta-box">

            <h2>
                Your Feedback Matters.
            </h2>

            <p>
                Help your college create a better learning
                environment. Share your experience today.
            </p>

            <a href="login.php" class="cta-button">
                Start Giving Feedback →
            </a>

        </div>

    </section>


    <!-- ================= FOOTER ================= -->

    <footer>

        <div>

            <div class="footer-name">
                SCFMS
            </div>

            <p>
                Smart College Feedback Management System
            </p>

        </div>

        <p>
            © 2026 SCFMS. All Rights Reserved.
        </p>

    </footer>

</body>

</html>