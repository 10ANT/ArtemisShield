<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ - ArtemisShield</title>

    <!-- Styles -->
    @include('partials.styles')
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <!-- Start Header Area -->
            @include('partials.header')
            <!-- End Header Area -->

            <!--  START: FAQ Content  -->
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                         <div class="card shadow-sm">
                            <div class="card-body p-4 p-md-5">
                                <h1 class="card-title text-center mb-5">Frequently Asked Questions (FAQ)</h1>
            
                                <div class="accordion" id="faqAccordion">
            
                                    {{-- General Questions --}}
                                    <h4 class="mt-4 text-primary">General</h4>
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                What is ArtemisShield?
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                ArtemisShield is a centralized dashboard for wildfire management. It integrates real-time satellite data, weather information, and field reports to provide a comprehensive operational picture for command officers, firefighters, and other emergency personnel.
                                            </div>
                                        </div>
                                    </div>
            
                                    {{-- Responsible AI Questions --}}
                                    <h4 class="mt-5 text-primary">AI, Transparency & Accountability</h4>
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Is the AI making decisions for me?
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                <strong>Absolutely not.</strong> The AI is a <strong>decision-support tool</strong> only. It provides suggestions, like predicted fire paths or high-risk zones, to help you make more informed decisions. The final authority and accountability for every command and action rests with the human user.
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                How does the fire spread prediction work? What data does it use?
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                The prediction model is an Azure Machine Learning algorithm. It analyzes multiple factors, including:
                                                <ul>
                                                    <li><strong>Current Fire Perimeter:</strong> Data from satellite imagery and field reports.</li>
                                                    <li><strong>Weather Data:</strong> Real-time wind speed, wind direction, humidity, and temperature.</li>
                                                    <li><strong>Topography:</strong> Terrain slope and elevation.</li>
                                                    <li><strong>Fuel Type:</strong> Type and condition of vegetation in the area.</li>
                                                </ul>
                                                It generates a probabilistic map of where the fire is most likely to spread. It is a forecast, not a guarantee.
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                Who is responsible if a prediction is inaccurate?
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                Accountability lies with the human decision-maker. The AI provides data-driven suggestions, but it is the responsibility of the command officer to interpret this information within the context of the overall situation and their own expertise. The system provides the best possible forecast with available data, but all models have limitations.
                                            </div>
                                        </div>
                                    </div>
            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  END: FAQ Content  -->

            <!-- Start Footer Area -->
            @include('partials.footer')
            <!-- End Footer Area -->
        </div>
    </div>

    @include('partials.theme_settings')
    @include('partials.scripts')
</body>
</html>