<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ArtemisShield - Wildfire Protection Dashboard</title>
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

				<div class="main-content-container overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <h3 class="mb-0">Account Settings</h3>

                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb align-items-center mb-0 lh-1">
                                <li class="breadcrumb-item">
                                    <a href="#" class="d-flex align-items-center text-decoration-none">
                                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                                        <span class="text-secondary fw-medium hover">Dashboard</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="fw-medium">Settings</span>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="fw-medium">Account</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                   
                    <div class="card bg-white border-0 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <ul class="ps-0 mb-4 list-unstyled d-flex flex-wrap gap-2 gap-lg-3">
                                <li>
                                    <a href="/account-settings" class="btn btn-primary border border-primary bg-primary text-white py-2 px-3 fw-semibold">Account Settings</a>
                                </li>
                                <li>
                                    <a href="/change-password" class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Change Password</a>
                                </li>
                                <li>
                                    <a href="/connections" class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Connections</a>
                                </li>
                                <li>
                                    <a href="/privacy-policy" class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="/terms-conditions" class="btn btn-primary border border-primary bg-transparent text-primary py-2 px-3 fw-semibold">Terms & Conditions</a>
                                </li>
                            </ul>

                            <div class="mb-4">
                                <h4 class="fs-20 mb-1">Profile</h4>
                                <p class="fs-15">Update your photo and personal details here.</p>
                            </div>
                            
                            <form>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">First Name</label>
                                            <div class="form-group position-relative">
                                                <input type="text" class="form-control text-dark ps-5 h-55" value="Olivia">
                                                <i class="ri-user-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Last Name</label>
                                            <div class="form-group position-relative">
                                                <input type="text" class="form-control text-dark ps-5 h-55" value="John">
                                                <i class="ri-user-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Email Address</label>
                                            <div class="form-group position-relative">
                                                <input type="email" class="form-control text-dark ps-5 h-55" value="">
                                                <i class="ri-mail-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Phone</label>
                                            <div class="form-group position-relative">
                                                <input type="text" class="form-control text-dark ps-5 h-55" value="+1 444 555 6699">
                                                <i class="ri-phone-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Address</label>
                                            <div class="form-group position-relative">
                                                <input type="text" class="form-control text-dark ps-5 h-55" value="E.g. 84 S. Arrowhead Court Branford">
                                                <i class="ri-phone-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Country</label>
                                            <div class="form-group position-relative">
                                                <select class="form-select form-control ps-5 h-55" aria-label="Default select example">
                                                    <option selected class="text-dark">Switzerland</option>
                                                    <option value="1" class="text-dark">United States</option>
                                                    <option value="2" class="text-dark">Canada</option>
                                                    <option value="3" class="text-dark">France</option>
                                                </select>
                                                <i class="ri-map-2-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Date Of Birth</label>
                                            <div class="form-group position-relative">
                                                <input type="date" class="form-control text-dark ps-5 h-55 text-gray-light">
                                                <i class="ri-calendar-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Gender</label>
                                            <div class="form-group position-relative">
                                                <select class="form-select form-control ps-5 h-55" aria-label="Default select example">
                                                    <option selected class="text-dark">Male</option>
                                                    <option value="1" class="text-dark">Female</option>
                                                    <option value="2" class="text-dark">Others</option>
                                                </select>
                                                <i class="ri-men-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Your Skills</label>
                                            <div class="form-group position-relative">
                                                <select class="form-select form-control ps-5 h-55" aria-label="Default select example">
                                                    <option selected class="text-dark">Project Management</option>
                                                    <option value="1" class="text-dark">Leadership</option>
                                                    <option value="2" class="text-dark">Data Analysis</option>
                                                    <option value="3" class="text-dark">Teamwork</option>
                                                    <option value="4" class="text-dark">Web Development</option>
                                                </select>
                                                <i class="ri-line-chart-fill position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Your Profession</label>
                                            <div class="form-group position-relative">
                                                <select class="form-select form-control ps-5 h-55" aria-label="Default select example">
                                                    <option selected class="text-dark">Software Developer</option>
                                                    <option value="1" class="text-dark">Financial Manager</option>
                                                    <option value="2" class="text-dark">IT Manager</option>
                                                    <option value="3" class="text-dark">Teamwork</option>
                                                    <option value="4" class="text-dark">Physician Assistant</option>
                                                </select>
                                                <i class="ri-honour-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Company Name</label>
                                            <div class="form-group position-relative">
                                                <input type="text" class="form-control text-dark ps-5 h-55" value="ArtemisShield - Wildfire Protection Dashboard">
                                                <i class="ri-phone-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Company Website</label>
                                            <div class="form-group position-relative">
                                                <input type="url" class="form-control text-dark ps-5 h-55" value="http://website.com">
                                                <i class="ri-phone-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Bio Data :</label>
                                            <div class="form-group position-relative">
                                                <textarea class="form-control ps-5 text-dark" placeholder="Bio Data ... " cols="30" rows="5"></textarea>
                                                <i class="ri-information-line position-absolute top-3 start-0 fs-20 text-gray-light ps-20 pt-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group only-file-upload">
                                            <label class="label text-secondary mb-1">Your Photo</label>
                                            <span class="d-block mb-3">This will be displayed on your profile.</span>
                                            <div class="form-control h-100 text-center position-relative p-4 p-lg-5">
                                                <div class="product-upload">
                                                    <label for="file-upload" class="file-upload mb-0">
                                                        <i class="ri-folder-image-line bg-primary bg-opacity-10 p-2 rounded-1 text-primary"></i>
                                                        <span class="d-block text-body fs-14">Drag and drop an image or <span class="text-primary text-decoration-underline">Browse</span></span>
                                                    </label>
                                                    <label class="position-absolute top-0 bottom-0 start-0 end-0 cursor" id="upload-container">
                                                        <input class="form__file bottom-0" id="upload-files" type="file" multiple="multiple">
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="files-list-container"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 mt-5">
                                    <h4 class="fs-20 mb-4">Socials Profile</h4>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Facebook</label>
                                            <div class="form-group position-relative">
                                                <input type="url" class="form-control text-dark ps-5 h-55" value="https://www.facebook.com/">
                                                <i class="ri-facebook-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">X</label>
                                            <div class="form-group position-relative">
                                                <input type="url" class="form-control text-dark ps-5 h-55" value="https://x.com/">
                                                <i class="ri-twitter-x-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">Linkedin</label>
                                            <div class="form-group position-relative">
                                                <input type="url" class="form-control text-dark ps-5 h-55" value="https://www.linkedin.com/">
                                                <i class="ri-linkedin-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <label class="label text-secondary">YouTube</label>
                                            <div class="form-group position-relative">
                                                <input type="url" class="form-control text-dark ps-5 h-55" value="https://www.youtube.com/">
                                                <i class="ri-youtube-line position-absolute top-50 start-0 translate-middle-y fs-20 text-gray-light ps-20"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex flex-wrap gap-3">
                                            <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button>
                                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i class="ri-check-line text-white fw-medium"></i> Upload Profile</button>
                                        </div>
                                    </div>
                                </div>     
                            </form>
                        </div>
                    </div>
                </div>

				<div class="flex-grow-1"></div>

				<!-- Start Footer Area -->
				@include('partials.footer')
				<!-- End Footer Area -->
			</div>
		</div>

        
        @include('partials.theme_settings')
        @include('partials.scripts')
    </body>
</html>
