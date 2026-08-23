<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GOV.UK | The best place to find government services and information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f3f2f1;
            color: #0b0c0c;
            line-height: 1.5;
        }
        
        a {
            color: #1d70b8;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header styles */
        .govuk-header {
            background-color: #0b0c0c;
            color: white;
            padding: 10px 0;
            border-bottom: 5px solid #1d70b8;
        }
        
        .govuk-header__container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .govuk-header__logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .govuk-header__logotype {
            font-size: 28px;
            font-weight: bold;
        }
        
        .govuk-header__logotype a {
            color: white;
        }
        
        .govuk-header__content {
            font-size: 16px;
        }
        
        /* Search bar */
        .govuk-search {
            background-color: white;
            padding: 20px 0;
            border-bottom: 1px solid #b1b4b6;
        }
        
        .govuk-search__container {
            display: flex;
            max-width: 500px;
        }
        
        .govuk-search__input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #0b0c0c;
            font-size: 16px;
        }
        
        .govuk-search__button {
            background-color: #1d70b8;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        
        .govuk-search__button:hover {
            background-color: #0b4e8a;
        }
        
        /* Popular section */
        .govuk-popular {
            background-color: white;
            padding: 25px 0 15px 0;
            border-bottom: 1px solid #b1b4b6;
        }
        
        .govuk-popular__title {
            font-size: 19px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #0b0c0c;
        }
        
        .govuk-popular__grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .govuk-popular__item {
            padding: 5px 0;
        }
        
        /* Main content */
        .govuk-main {
            padding: 30px 0;
            background-color: white;
        }
        
        .govuk-main-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }
        
        @media (max-width: 768px) {
            .govuk-main-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
        /* Services section */
        .govuk-section-title {
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1d70b8;
        }
        
        .govuk-services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .govuk-service-card {
            border-top: 3px solid #1d70b8;
            padding: 15px;
            background-color: #f8f8f8;
            min-height: 130px;
        }
        
        .govuk-service-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .govuk-service-card p {
            color: #505a5f;
            font-size: 14px;
        }
        
        /* Featured section */
        .govuk-featured {
            background-color: #f3f2f1;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .govuk-featured h3 {
            margin-bottom: 15px;
        }
        
        .govuk-featured-list {
            list-style-type: none;
        }
        
        .govuk-featured-list li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        
        .govuk-featured-list li:before {
            content: "•";
            color: #1d70b8;
            font-size: 20px;
            position: absolute;
            left: 0;
        }
        
        .govuk-featured-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #b1b4b6;
        }
        
        .govuk-featured-image {
            width: 100px;
            height: 70px;
            background-color: #dde0e2;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #505a5f;
            font-size: 12px;
            font-weight: bold;
        }
        
        .govuk-featured-content {
            flex: 1;
        }
        
        /* Government activity */
        .govuk-activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }
        
        .govuk-activity-card {
            padding: 15px;
            border: 1px solid #b1b4b6;
        }
        
        .govuk-activity-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .govuk-activity-card p {
            color: #505a5f;
            font-size: 14px;
        }
        
        /* More on GOV.UK section */
        .govuk-more-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        /* Footer */
        .govuk-footer {
            background-color: #0b0c0c;
            color: white;
            padding: 30px 0;
            margin-top: 40px;
        }
        
        .govuk-footer__container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .govuk-footer__section {
            flex: 1;
            min-width: 250px;
        }
        
        .govuk-footer__section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .govuk-footer__links {
            list-style-type: none;
        }
        
        .govuk-footer__links li {
            margin-bottom: 8px;
        }
        
        .govuk-footer__links a {
            color: white;
        }
        
        .govuk-footer__copyright {
            width: 100%;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #505a5f;
            color: #b1b4b6;
        }
        
        /* Utility classes */
        .govuk-tag {
            background-color: #1d70b8;
            color: white;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-right: 10px;
        }
        
        .govuk-heading-l {
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        .govuk-heading-m {
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .govuk-heading-s {
            font-size: 19px;
            margin-bottom: 10px;
        }
        
        .govuk-body {
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .govuk-body-s {
            font-size: 14px;
            color: #505a5f;
        }
        
        /* Feedback section */
        .govuk-feedback {
            background-color: #f3f2f1;
            padding: 20px;
            margin-top: 40px;
            border-top: 1px solid #b1b4b6;
        }
        
        .govuk-feedback__question {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .govuk-feedback__buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .govuk-feedback__button {
            padding: 8px 15px;
            background-color: #dde0e2;
            border: 1px solid #b1b4b6;
            cursor: pointer;
        }
        
        .govuk-feedback__button:hover {
            background-color: #cbd2d8;
        }
        
        .govuk-feedback__link {
            font-size: 14px;
        }
        
        /* App promo */
        .govuk-app-promo {
            background-color: #1d70b8;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .govuk-app-promo a {
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="govuk-header">
        <div class="container govuk-header__container">
            <div class="govuk-header__logo">
                <div class="govuk-header__logotype">
                    <a href="#">GOV.UK</a>
                </div>
            </div>
            <div class="govuk-header__content">
                <span>Welcome to GOV.UK</span>
            </div>
        </div>
    </header>

    <!-- Search -->
    <section class="govuk-search">
        <div class="container">
            <div class="govuk-search__container">
                <input type="text" class="govuk-search__input" placeholder="Search GOV.UK" aria-label="Search GOV.UK">
                <button class="govuk-search__button">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </section>

    <!-- Popular on GOV.UK -->
    <section class="govuk-popular">
        <div class="container">
            <div class="govuk-popular__title">Popular on GOV.UK</div>
            <div class="govuk-popular__grid">
                <div class="govuk-popular__item">
                    <a href="/log-in-register-hmrc-online-services">HMRC account: sign in or set up</a>
                </div>
                <div class="">
                    <a href="evisa-registration.php">eVisas: access and use your online immigration status</a>
                </div>
                <div class="govuk-popular__item">
                    <a href="/sign-in-universal-credit">Universal Credit account: sign in</a>
                </div>
                <div class="govuk-popular__item">
                    <a href="/personal-tax-account">Personal tax account: sign in or set up</a>
                </div>
                <div class="govuk-popular__item">
                    <a href="/sign-in-childcare-account">Childcare account: sign in</a>
                </div>
                <div class="govuk-popular__item">
                    <a href="/check-state-pension">Check your State Pension forecast</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="govuk-main">
        <div class="container">
            <h1 class="govuk-heading-l">The best place to find government services and information</h1>
            
            <div class="govuk-main-wrapper">
                <!-- Left column: Services and Information -->
                <div>
                    <h2 class="govuk-section-title">Services and information</h2>
                    
                    <div class="govuk-services-grid">
                        <div class="govuk-service-card">
                            <h3><a href="/browse/benefits">Benefits</a></h3>
                            <p>Includes eligibility, appeals, tax credits and Universal Credit</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/births-deaths-marriages">Births, deaths, marriages and care</a></h3>
                            <p>Parenting, civil partnerships, divorce and Lasting Power of Attorney</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/business">Business and self-employed</a></h3>
                            <p>Tools and guidance for businesses</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/childcare-parenting">Childcare and parenting</a></h3>
                            <p>Includes giving birth, fostering, adopting, benefits for children, childcare and schools</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/citizenship">Citizenship and living in the UK</a></h3>
                            <p>Voting, community participation, life in the UK, international projects</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/justice">Crime, justice and the law</a></h3>
                            <p>Legal processes, courts and the police</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/disabilities">Disabled people</a></h3>
                            <p>Includes carers, your rights, benefits and the Equality Act</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/driving">Driving and transport</a></h3>
                            <p>Includes vehicle tax, MOT and driving licences</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/education">Education and learning</a></h3>
                            <p>Includes student loans, admissions and apprenticeships</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/employing-people">Employing people</a></h3>
                            <p>Includes pay, contracts, hiring and redundancies</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/environment-countryside">Environment and countryside</a></h3>
                            <p>Includes flooding, recycling, farming and wildlife</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/housing-local-services">Housing and local services</a></h3>
                            <p>Owning or renting and council services</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/tax">Money and tax</a></h3>
                            <p>Includes debt and Self Assessment</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/abroad">Passports, travel and living abroad</a></h3>
                            <p>Includes renewing passports and travel advice by country</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/visas-immigration">Visas and immigration</a></h3>
                            <p>Apply to visit, work, study, settle or seek asylum in the UK</p>
                        </div>
                        
                        <div class="govuk-service-card">
                            <h3><a href="/browse/working">Working, jobs and pensions</a></h3>
                            <p>Includes holidays, finding a job and redundancy</p>
                        </div>
                    </div>
                    
                    <h2 class="govuk-section-title">Government activity</h2>
                    <p class="govuk-body">Find out what the government is doing</p>
                    
                    <div class="govuk-activity-grid">
                        <div class="govuk-activity-card">
                            <h3><a href="/government/organisations">Departments</a></h3>
                            <p>Departments, agencies and public bodies</p>
                        </div>
                        
                        <div class="govuk-activity-card">
                            <h3><a href="/search/news-and-communications">News</a></h3>
                            <p>News stories, speeches, letters and notices</p>
                        </div>
                        
                        <div class="govuk-activity-card">
                            <h3><a href="/search/guidance-and-regulation">Guidance and regulation</a></h3>
                            <p>Detailed guidance, regulations and rules</p>
                        </div>
                        
                        <div class="govuk-activity-card">
                            <h3><a href="/search/research-and-statistics">Research and statistics</a></h3>
                            <p>Reports, analysis and official statistics</p>
                        </div>
                        
                        <div class="govuk-activity-card">
                            <h3><a href="/search/policy-papers-and-consultations">Policy papers and consultations</a></h3>
                            <p>Consultations and strategy</p>
                        </div>
                        
                        <div class="govuk-activity-card">
                            <h3><a href="/search/transparency-and-freedom-of-information-releases">Transparency documents</a></h3>
                            <p>Data, Freedom of Information releases and corporate reports</p>
                        </div>
                    </div>
                    
                    <!-- More on GOV.UK section -->
                    <h2 class="govuk-section-title" style="margin-top: 40px;">More on GOV.UK</h2>
                    <div class="govuk-more-grid">
                        <div><a href="/log-in-register-hmrc-online-services">HMRC services: sign in</a></div>
                        <div><a href="/check-mot-history">Check MOT history of a vehicle</a></div>
                        <div><a href="/vehicle-tax">Tax your vehicle</a></div>
                        <div><a href="/universal-credit">Universal Credit</a></div>
                        <div><a href="/foreign-travel-advice">Foreign travel advice</a></div>
                        <div><a href="/state-pension-age">Check your State Pension age</a></div>
                        <div><a href="/sign-in-childcare-account">Childcare account: sign in</a></div>
                        <div><a href="/student-finance-register-login">Student finance: sign in</a></div>
                        <div><a href="/self-assessment-tax-returns">Self Assessment tax returns</a></div>
                        <div><a href="/apply-renew-passport">Apply for a passport</a></div>
                    </div>
                </div>
                
                <!-- Right column: Featured -->
                <div>
                    <div class="govuk-featured">
                        <h3 class="govuk-heading-m">Featured</h3>
                        
                        <div class="govuk-featured-item">
                            <div class="govuk-featured-image">
                                Image placeholder<br>[81, 172, 181, 242]
                            </div>
                            <div class="govuk-featured-content">
                                <h4 class="govuk-heading-s"><a href="/self-assessment-tax-returns">Self Assessment</a></h4>
                                <p class="govuk-body-s">Find out about Self Assessment tax returns and when they are due.</p>
                            </div>
                        </div>
                        
                        <div class="govuk-featured-item">
                            <div class="govuk-featured-image">
                                Image placeholder<br>[81, 280, 181, 351]
                            </div>
                            <div class="govuk-featured-content">
                                <h4 class="govuk-heading-s"><a href="/find-a-job">Find a job</a></h4>
                                <p class="govuk-body-s">Search and apply for jobs in England, Scotland and Wales.</p>
                            </div>
                        </div>
                        
                        <div class="govuk-featured-item">
                            <div class="govuk-featured-image">
                                Image placeholder<br>[81, 384, 181, 455]
                            </div>
                            <div class="govuk-featured-content">
                                <h4 class="govuk-heading-s"><a href="/check-national-insurance-record">National Insurance</a></h4>
                                <p class="govuk-body-s">Check your record to see if you can add more contributions.</p>
                            </div>
                        </div>
                        
                        <div class="govuk-featured-item">
                            <div class="govuk-featured-image">
                                Image placeholder<br>[81, 550, 181, 622]
                            </div>
                            <div class="govuk-featured-content">
                                <h4 class="govuk-heading-s"><a href="/guidance/download-the-govuk-app">Get the GOV.UK app</a></h4>
                                <p class="govuk-body-s">Your government services and information, on the go.</p>
                            </div>
                        </div>
                        
                        <div class="govuk-app-promo">
                            <p><a href="/guidance/download-the-govuk-app">Get the GOV.UK app →</a><br>
                            <span class="govuk-body-s">Your government services and information, on the go.</span></p>
                        </div>
                    </div>
                    
                    <div class="govuk-featured">
                        <h3 class="govuk-heading-m">Popular services</h3>
                        <ul class="govuk-featured-list">
                            <li><a href="/log-in-register-hmrc-online-services">HMRC account: sign in or set up</a></li>
                            <li><a href="evisa-registration.html">eVisas: access and use your online immigration status</a></li>
                            <li><a href="/sign-in-universal-credit">Universal Credit account: sign in</a></li>
                            <li><a href="/personal-tax-account">Personal tax account: sign in or set up</a></li>
                            <li><a href="/sign-in-childcare-account">Childcare account: sign in</a></li>
                            <li><a href="/check-state-pension">Check your State Pension forecast</a></li>
                            <li><a href="/check-mot-history">Check MOT history of a vehicle</a></li>
                            <li><a href="/vehicle-tax">Tax your vehicle</a></li>
                            <li><a href="/apply-renew-passport">Apply for a passport</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Feedback Section -->
            <div class="govuk-feedback">
                <div class="govuk-feedback__question">Is this page useful?</div>
                <div class="govuk-feedback__buttons">
                    <button class="govuk-feedback__button">Yes</button>
                    <button class="govuk-feedback__button">No</button>
                </div>
                <div class="govuk-feedback__link">
                    <a href="#">Help us improve GOV.UK</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="govuk-footer">
        <div class="container govuk-footer__container">
            <div class="govuk-footer__section">
                <h3>Services and information</h3>
                <ul class="govuk-footer__links">
                    <li><a href="/browse/benefits">Benefits</a></li>
                    <li><a href="/browse/births-deaths-marriages">Births, deaths, marriages</a></li>
                    <li><a href="/browse/business">Business</a></li>
                    <li><a href="/browse/childcare-parenting">Childcare</a></li>
                    <li><a href="/browse/citizenship">Citizenship</a></li>
                </ul>
            </div>
            
            <div class="govuk-footer__section">
                <h3>Departments and policy</h3>
                <ul class="govuk-footer__links">
                    <li><a href="/government/organisations">Departments</a></li>
                    <li><a href="/search/news-and-communications">News</a></li>
                    <li><a href="/search/guidance-and-regulation">Guidance</a></li>
                    <li><a href="/search/research-and-statistics">Statistics</a></li>
                    <li><a href="/search/policy-papers-and-consultations">Consultations</a></li>
                </ul>
            </div>
            
            <div class="govuk-footer__section">
                <h3>Support links</h3>
                <ul class="govuk-footer__links">
                    <li><a href="#">Help</a></li>
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">Accessibility</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            
            <div class="govuk-footer__copyright">
                <p>© Crown copyright. All content is available under the Open Government Licence v3.0, except where otherwise stated.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple search functionality
        document.querySelector('.govuk-search__button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.govuk-search__input').value;
            if(searchTerm.trim()) {
                alert(`Searching for: "${searchTerm}"\n\nIn a real implementation, this would redirect to search results.`);
            }
        });
        
        // Allow pressing Enter to search
        document.querySelector('.govuk-search__input').addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                document.querySelector('.govuk-search__button').click();
            }
        });
        
        // Feedback functionality
        document.querySelectorAll('.govuk-feedback__button').forEach(button => {
            button.addEventListener('click', function() {
                const isUseful = this.textContent === 'Yes';
                alert(`Thank you for your feedback. You selected: "${this.textContent}"\n\nThis helps us improve the website.`);
                
                // Reset button styles
                document.querySelectorAll('.govuk-feedback__button').forEach(btn => {
                    btn.style.backgroundColor = '#dde0e2';
                });
                
                // Highlight selected button
                this.style.backgroundColor = isUseful ? '#cce2d8' : '#f7d4d4';
            });
        });
        
        // Simple navigation for demonstration
        document.querySelectorAll('.govuk-service-card a, .govuk-activity-card a, .govuk-featured-list a, .govuk-popular__item a, .govuk-more-grid a, .govuk-featured-content a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageName = this.textContent;
                alert(`Navigating to: "${pageName}"\n\nIn a real implementation, this would load the appropriate content.`);
            });
        });
    </script>
</body>
</html>