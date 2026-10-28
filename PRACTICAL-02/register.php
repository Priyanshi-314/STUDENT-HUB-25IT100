<?php
declare(strict_types=1);

require_once __DIR__ . '/../PRACTICAL-10/session.php';
sh_boot();
$csrfToken = sh_csrf_token();
?>
<!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StudentHub - Register</title>
  <link rel="stylesheet" href="../PRACTICAL-03/css/style.css">
  <link rel="stylesheet" href="../PRACTICAL-05/css/registration.css">
  <script src="../PRACTICAL-05/js/validation.js" defer></script>
</head>

<body>
<a class="skip-link" href="#main-content">Skip to main content</a>

    <!-- ================= HEADER ================= -->

    <header>
        <h1>🎓 StudentHub</h1>
        <p>Create Your Student Account</p>
    </header>


    <!-- ================= NAVIGATION ================= -->

    <nav aria-label="Primary navigation">
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="register.php" aria-current="page">Register</a>
        <a href="login.php">Login</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="events.html">Events</a>
        <a href="profile.html">Profile</a>
        <a href="contact.html">Contact</a>
        <a href="admin.php">Admin</a>
        <a href="faq.html">FAQ</a>
        <a href="feedback.html">Feedback</a>
    </nav>


    <!-- ================= MAIN ================= -->

    <main id="main-content">

        <!-- Registration Introduction -->

        <section class="register-intro">

            <p class="section-tag">STUDENT REGISTRATION</p>

            <h2>Create Your StudentHub Account</h2>

            <p>
                Join StudentHub and access student services, academic
                information, college events, announcements and much more
                from one place.
            </p>

        </section>


        <!-- ================= REGISTRATION FORM ================= -->

        <section class="registration-container">

            <div class="form-heading">

                <h2>Registration Form</h2>

                <p>
                    Please enter your details carefully.
                    All fields marked with <span>*</span> are required.
                </p>

            </div>


            <form id="registrationForm" action="../PRACTICAL-09/register.php" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">


                <!-- ================= PERSONAL DETAILS ================= -->

                <div class="form-section">

                    <h3>👤 Personal Information</h3>

                    <div class="form-grid">


                        <!-- Full Name -->

                        <div class="form-group">

                            <label for="name">
                                Full Name <span>*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                aria-describedby="nameError"
                                required
                            >

                            <small id="nameError" class="error-message"></small>

                        </div>


                        <!-- Username is used for sign-in and checked for duplicates on the server. -->
                        <div class="form-group">
                            <label for="username">Username <span>*</span></label>
                            <input type="text" id="username" name="username" minlength="4" maxlength="24" pattern="[A-Za-z0-9._-]{4,24}" autocomplete="username" aria-describedby="usernameError" placeholder="Choose 4–24 letters or numbers" required>
                            <small id="usernameError" class="error-message"></small>
                        </div>
                        <!-- Email -->

                        <div class="form-group">

                            <label for="email">
                                Email Address <span>*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="example@student.com"
                                autocomplete="email"
                                aria-describedby="emailError"
                                required
                            >

                            <small id="emailError" class="error-message"></small>

                        </div>


                        <!-- Mobile -->

                        <div class="form-group">

                            <label for="phone">
                                Mobile Number <span>*</span>
                            </label>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                pattern="[6-9][0-9]{9}"
                                title="Enter a 10-digit Indian mobile number beginning with 6, 7, 8, or 9"
                                placeholder="Enter 10-digit mobile number"
                                inputmode="numeric"
                                maxlength="10"
                                autocomplete="tel"
                                aria-describedby="phoneError"
                                required
                            >

                            <small id="phoneError" class="error-message"></small>

                        </div>


                        <!-- Date of Birth -->

                        <div class="form-group">

                            <label for="dob">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                id="dob"
                                name="dob"
                                autocomplete="bday"
                            >

                        </div>


                    </div>

                </div>



                <!-- ================= ACADEMIC DETAILS ================= -->

                <div class="form-section">

                    <h3>🎓 Academic Information</h3>

                    <div class="form-grid">


                        <!-- Course -->

                        <div class="form-group">

                            <label for="course">
                                Course <span>*</span>
                            </label>

                            <select
                                id="course"
                                name="course"
                                aria-describedby="courseError"
                                required
                            >

                                <option value="">
                                    Select your course
                                </option>

                                <option value="BCA">
                                    BCA
                                </option>

                                <option value="BSc IT">
                                    B.Sc. Information Technology
                                </option>

                                <option value="BTech CSE">
                                    B.Tech Computer Science
                                </option>

                                <option value="BTech IT">
                                    B.Tech Information Technology
                                </option>

                                <option value="BCom">
                                    B.Com
                                </option>

                                <option value="BA">
                                    B.A.
                                </option>

                            </select>

                            <small id="courseError" class="error-message"></small>

                        </div>


                        <!-- Year -->

                        <div class="form-group">

                            <label for="year">
                                Year of Study <span>*</span>
                            </label>

                            <select
                                id="year"
                                name="year"
                                aria-describedby="yearError"
                                required
                            >

                                <option value="">
                                    Select year
                                </option>

                                <option value="1">
                                    First Year
                                </option>

                                <option value="2">
                                    Second Year
                                </option>

                                <option value="3">
                                    Third Year
                                </option>

                                <option value="4">
                                    Fourth Year
                                </option>

                            </select>

                            <small id="yearError" class="error-message"></small>

                        </div>


                        <!-- Department -->

                        <div class="form-group">

                            <label for="department">
                                Department
                            </label>

                            <select
                                id="department"
                                name="department"
                            >

                                <option value="">
                                    Select department
                                </option>

                                <option value="IT">
                                    Information Technology
                                </option>

                                <option value="CSE">
                                    Computer Engineering
                                </option>

                                <option value="ME">
                                    Mechanical Engineering
                                </option>

                                <option value="EE">
                                    Electrical Engineering
                                </option>

                                <option value="CE">
                                    Civil Engineering
                                </option>

                            </select>

                        </div>


                    </div>

                </div>



                <!-- ================= GENDER ================= -->

                <div class="form-section">

                    <h3>⚧ Basic Details</h3>

                    <div class="form-group">

                        <fieldset aria-describedby="genderError">

                            <legend>
                                Gender <span>*</span>
                            </legend>

                            <div class="radio-group">

                                <label>
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="male"
                                        required
                                    >
                                    Male
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="female"
                                    >
                                    Female
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="other"
                                    >
                                    Other
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="gender"
                                        value="prefer-not"
                                    >
                                    Prefer not to say
                                </label>

                            </div>

                        </fieldset>

                        <small id="genderError" class="error-message"></small>

                    </div>

                </div>



                <!-- ================= PASSWORD ================= -->

                <div class="form-section">

                    <h3>🔐 Account Security</h3>

                    <div class="form-grid">


                        <!-- Password -->

                        <div class="form-group">

                            <label for="password">
                                Password <span>*</span>
                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                minlength="8"
                                placeholder="Create a strong password"
                                autocomplete="new-password"
                                aria-describedby="passwordError passwordStrength"
                                required
                            >

                            <div
                                id="passwordStrength"
                                class="password-strength"
                                role="status"
                                aria-live="polite"
                            >
                                Password strength will appear here
                            </div>

                            <small
                                id="passwordError"
                                class="error-message"
                            ></small>

                        </div>


                        <!-- Confirm Password -->

                        <div class="form-group">

                            <label for="confirm">
                                Confirm Password <span>*</span>
                            </label>

                            <input
                                type="password"
                                id="confirm"
                                name="confirm"
                                minlength="8"
                                placeholder="Re-enter your password"
                                autocomplete="new-password"
                                aria-describedby="confirmError"
                                required
                            >

                            <small
                                id="confirmError"
                                class="error-message"
                            ></small>

                        </div>


                    </div>

                </div>



                <!-- ================= ADDRESS ================= -->

                <div class="form-section">

                    <h3>📍 Contact Information</h3>

                    <div class="form-group">

                        <label for="address">
                            Address
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            placeholder="Enter your address"
                            autocomplete="street-address"
                        ></textarea>

                    </div>

                </div>



                <!-- ================= HOBBIES ================= -->

                <div class="form-section">

                    <h3>⭐ Interests</h3>

                    <fieldset>

                        <legend>
                            Select your interests
                        </legend>

                        <div class="checkbox-group">

                            <label>
                                <input
                                    type="checkbox"
                                    id="reading"
                                    name="hobbies[]"
                                    value="reading"
                                >
                                📚 Reading
                            </label>

                            <label>
                                <input
                                    type="checkbox"
                                    id="sports"
                                    name="hobbies[]"
                                    value="sports"
                                >
                                ⚽ Sports
                            </label>

                            <label>
                                <input
                                    type="checkbox"
                                    id="music"
                                    name="hobbies[]"
                                    value="music"
                                >
                                🎵 Music
                            </label>

                            <label>
                                <input
                                    type="checkbox"
                                    id="coding"
                                    name="hobbies[]"
                                    value="coding"
                                >
                                💻 Coding
                            </label>

                        </div>

                    </fieldset>

                </div>



                <!-- ================= TERMS ================= -->

                <div class="terms-section">

                    <label class="terms-label">

                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            value="accepted"
                            aria-describedby="formNotice"
                            required
                        >

                        <span>
                            I agree that StudentHub may use these details to create my student account and confirm that the information provided is correct. <span>*</span>
                        </span>

                    </label>

                    <small
                        id="formNotice"
                        class="error-message"
                    ></small>

                </div>



                <!-- ================= FORM BUTTONS ================= -->

                <div class="form-buttons">

                    <button
                        type="submit"
                        id="registerBtn"
                    >
                        Create Account →
                    </button>

                    <button
                        type="reset"
                        id="resetBtn"
                    >
                        Clear Form
                    </button>

                </div>


                <!-- Success message -->

                <div
                    id="formSuccess"
                    class="success-message"
                    role="status"
                    aria-live="polite"
                ></div>


            </form>

        </section>



        <!-- ================= LOGIN REMINDER ================= -->

        <section class="login-reminder">

            <h3>Already have an account?</h3>

            <p>
                Login to your StudentHub account and continue
                managing your student activities.
            </p>

            <a href="login.php">
                Go to Login →
            </a>

        </section>


    </main>



    <!-- ================= FOOTER ================= -->

    <footer>

        <p>
            &copy; 2026 StudentHub. All Rights Reserved.
        </p>

        <p>
            Email: support@studenthub.com
        </p>

    </footer>


    <!-- Practical 5 validation is loaded in the document head. -->
</body>

</html>
