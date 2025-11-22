<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IBADHI ISLAMIC PRE & PRIMARY SCHOOL – Shinyanga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* Rangi zenye kuendana na logo yako */
            --brand-magenta: #b93173;   /* ring ya pink/maroon */
            --brand-green:   #118a3b;   /* SHINYANGA text */
            --brand-gold:    #f5a623;   /* dome orange */
            --brand-blue:    #1e88e5;   /* kalamu ya bluu */
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .navbar-brand-logo {
            height: 48px;
            width: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.8);
            background: #fff;
        }

        .hero-section {
            background: radial-gradient(circle at top, var(--brand-magenta) 0, #2b0b33 45%, #05040a 100%);
            color: #fff;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .hero-badge {
            background: rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.25rem 0.9rem;
            font-size: 0.8rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .section-title {
            border-left: 4px solid var(--brand-magenta);
            padding-left: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: .9rem;
            letter-spacing: .08em;
            color: var(--brand-magenta);
        }

        .pill-tag {
            display: inline-block;
            padding: .15rem .6rem;
            border-radius: 999px;
            font-size: .7rem;
            background: rgba(17,138,59,0.08);
            color: var(--brand-green);
            border: 1px solid rgba(17,138,59,0.2);
        }

        .footer {
            background: #111827;
            color: #9ca3af;
        }

        .footer a {
            color: #e5e7eb;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--brand-magenta);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#top">
            <img src="{{ asset('images/school-logo.png') }}" alt="School logo" class="navbar-brand-logo">
            <div class="d-flex flex-column lh-1">
                <span class="fw-bold">Ibadhi Islamic</span>
                <small class="text-light">Pre & Primary School – Shinyanga</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#academics">Academics</a></li>
                <li class="nav-item"><a class="nav-link" href="#admissions">Admissions</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm px-3">
                        Login to Portal
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- HERO SECTION --}}
<section id="top" class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="hero-badge mb-3 d-inline-flex align-items-center gap-2">
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">
                        STRIVE TO EXCELL
                    </span>
                    <span class="text-white-50 small">Shinyanga, Tanzania</span>
                </div>

                <h1 class="display-5 fw-bold mb-3">
                    Holistic Islamic & Academic Excellence
                </h1>

                <p class="lead mb-4 text-white-50">
                    Ibadhi Islamic Pre & Primary School nurtures learners spiritually,
                    morally and academically – preparing them to excel in this world and the hereafter.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="#admissions" class="btn btn-warning text-dark fw-semibold">
                        Apply for Admission
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light">
                        Parent / Staff Login
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-3 small text-white-50">
                    <div><span class="pill-tag">Qur'an & Islamic Studies</span></div>
                    <div><span class="pill-tag">NECTA-Aligned Curriculum</span></div>
                    <div><span class="pill-tag">Modern Learning Environment</span></div>
                </div>
            </div>

            <div class="col-lg-5 ms-lg-auto">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase small fw-bold mb-3 text-muted">
                            Quick facts
                        </h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <span class="fw-semibold text-dark">Location:</span>
                                <span class="text-muted">Shinyanga Municipality</span>
                            </li>
                            <li class="mb-2">
                                <span class="fw-semibold text-dark">Levels:</span>
                                <span class="text-muted">Pre-school & Primary</span>
                            </li>
                            <li class="mb-2">
                                <span class="fw-semibold text-dark">Streams:</span>
                                <span class="text-muted">Islamic & Secular integration</span>
                            </li>
                            <li class="mb-2">
                                <span class="fw-semibold text-dark">Motto:</span>
                                <span class="text-muted">“Strive to Excell”</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-center small text-muted py-2">
                        For parents, guardians & staff – use the login button to access results.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT SECTION --}}
<section id="about" class="py-5 bg-light">
    <div class="container">
        <div class="section-title mb-3">About the school</div>
        <div class="row g-4">
            <div class="col-lg-7">
                <h3 class="fw-bold mb-3">Who we are</h3>
                <p class="text-muted">
                    Ibadhi Islamic Pre & Primary School is a faith-based institution committed to
                    producing well-rounded learners with strong Islamic values and academic excellence.
                </p>
                <p class="text-muted">
                    Our teaching approach combines Qur'an memorization, Islamic manners and a strong
                    foundation in core subjects such as Mathematics, Science and Languages.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Why parents choose us</h6>
                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-2">• Strong Islamic environment and discipline</li>
                            <li class="mb-2">• Qualified and caring teachers</li>
                            <li class="mb-2">• Close follow-up between school and parents</li>
                            <li class="mb-2">• Competitive performance in national examinations</li>
                            <li>• Safe and friendly learning environment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ACADEMICS SECTION --}}
<section id="academics" class="py-5">
    <div class="container">
        <div class="section-title mb-3">Academics</div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Pre-School</h5>
                        <p class="small text-muted">
                            Early childhood education focusing on Islamic manners, Arabic basics,
                            play-based learning and school readiness skills.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Primary School</h5>
                        <p class="small text-muted">
                            Full NECTA curriculum integrated with Qur'an, Islamic Studies and character building,
                            preparing learners for higher levels of education.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Co-curricular</h5>
                        <p class="small text-muted">
                            Co-curricular activities such as debates, sports and clubs to build confidence,
                            teamwork and leadership among pupils.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ADMISSIONS SECTION --}}
<section id="admissions" class="py-5 bg-light">
    <div class="container">
        <div class="section-title mb-3">Admissions</div>
        <div class="row g-4">
            <div class="col-lg-7">
                <h3 class="fw-bold mb-3">Join Ibadhi Islamic Pre & Primary School</h3>
                <p class="text-muted">
                    Admissions are open for both Pre-school and Primary levels. Parents are encouraged to visit
                    the school office for detailed information on fees, requirements and interview dates.
                </p>
                <p class="text-muted mb-3">
                    You may also contact the school using the details provided below to inquire about available
                    spaces, transport and other services.
                </p>
                <a href="#contact" class="btn btn-outline-primary btn-sm">
                    Contact admissions office
                </a>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Basic requirements</h6>
                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-2">• Birth certificate copy</li>
                            <li class="mb-2">• Recent passport size photos</li>
                            <li class="mb-2">• Previous school report (for transfers)</li>
                            <li>• Parent / guardian contact details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CONTACT SECTION --}}
<section id="contact" class="py-5">
    <div class="container">
        <div class="section-title mb-3">Contact</div>
        <div class="row g-4">
            <div class="col-lg-6">
                <h4 class="fw-bold mb-3">Get in touch</h4>
                <p class="text-muted small">
                    For inquiries about admissions, fees, or general information, please use the contacts below.
                </p>
                <ul class="list-unstyled small text-muted mb-3">
                    <li class="mb-2"><strong>Address:</strong> Shinyanga, Tanzania</li>
                    <li class="mb-2"><strong>Phone:</strong> +255699617660</li>
                    <li class="mb-2"><strong>Email:</strong> info@ibadhiislamic.ac.tz</li>
                </ul>
                <p class="small text-muted mb-1">
                    Parents and staff can use the login button on the top-right to access the online results portal.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Quick message</h6>
                        <p class="small text-muted mb-3">
                            Send us a short message and the school office will get back to you.
                        </p>
                        <form>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Your name">
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" placeholder="Phone or email">
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" rows="3" placeholder="Your message"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm">
                                Send message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="footer py-3 mt-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small">
            &copy; {{ date('Y') }} Ibadhi Islamic Pre & Primary School – Shinyanga. All rights reserved.
        </div>
        <div class="small">
            <a href="{{ route('login') }}">Portal Login</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
