<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Application Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="create.php" method="post"> 
        <div class="container">
            <h1>Volunteer Application Form</h1>
            <h3>Full Name</h3>
            <div class="fullname">
                <div class="text-box">
                    <input type="text" name="firstname" placeholder="First Name" required>
                </div>
                <div class="text-box">
                    <input type="text" name="lastname" placeholder="Last Name" required>
                </div>
            </div>

            <h3>Current Address</h3>

            <div class="text-box">
                <input type="text" name="street" placeholder="Street Address" required>
            </div>
            <div class="address">
            <div class="text-box">
                <input type="text" name="addressline" placeholder="Street Address Line Two">
            </div>
            <div class="text-box">
                <input type="text" name="city" placeholder="City" required>
            </div>
            </div>
            <div class="address">
            <div class="text-box">
                <input type="text" name="province" placeholder="State/Province" required>
            </div>
            <div class="text-box">
                <input type="text" name="zip" placeholder="Postal/Zip Code" required>
            </div>
            </div>

            <h3>Email Address</h3>
            <div class="text-box">
                <input type="email" name="email" placeholder="ex. myname@example.com" required>
            </div>

            <h3>Date of Birth:</h3>
            <div class="text-box">
                <input type="date" name="dateofbirth" required>
            </div>

            <h3>Gender</h3>
            <div class="radio-buttons">
                <div class="text-box">
                    <input type="radio" name="gender" value="Male" id="male">
                    <label for="male">Male</label>
                </div>
                <div class="text-box">
                    <input type="radio" name="gender" value="Female" id="female">
                    <label for="female">Female</label>
                </div>
                <div class="text-box">
                    <input type="radio" name="gender" value="N/A" id="na">
                    <label for="na">N/A</label>
                </div>
            </div>

            <h3>Current Occupation</h3>
            <div class="radio-buttons">
                <div class="text-box">
                    <input type="radio" id="student" name="occupation" value="Student">
                    <label for="student">Student</label>
                </div>
                <div class="text-box">
                    <input type="radio" id="fulltime" name="occupation" value="Full Time">
                    <label for="fulltime">Full Time</label>
                </div>
                <div class="text-box">
                    <input type="radio" id="parttime" name="occupation" value="Part Time">
                    <label for="parttime">Part Time</label>
                </div>
                <div class="text-box">
                    <input type="radio" id="other" name="occupation" value="Other">
                    <label for="other">Other</label>
                </div>
            </div>

            <h3>Details of Occupation</h3>
            <div class="text-box">
                <input type="text" name="details" placeholder="Please provide relevant details about your current occupation">
            </div>

            <h3>Emergency Contacts</h3>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Relationship</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Contact</td>
                        <td><div class="text-box"><input type="text" name="emergencycontactname1" placeholder="Enter Name" required></div></td>
                        <td><div class="text-box"><input type="text" name="emergencycontactphone1" placeholder="Enter Phone Number" required></div></td>
                        <td><div class="text-box"><input type="text" name="emergencycontactrelationship1" placeholder="Enter Relationship" required></div></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><div class="text-box"><input type="text" name="emergencycontactname2" placeholder="Enter Name"></div></td>
                        <td><div class="text-box"><input type="text" name="emergencycontactphone2" placeholder="Enter Phone Number"></div></td>
                        <td><div class="text-box"><input type="text" name="emergencycontactrelationship2" placeholder="Enter Relationship"></div></td>
                    </tr>
                </tbody>
            </table>

            <h3>Why do you want to become a volunteer?</h3>
            <div class="text-box">
                <input type="text" name="reasontovolunteer" placeholder="Please tell us why you want to become a volunteer" required>
            </div>

            <h3>Please tell us your hobbies and interests briefly</h3>
            <div class="text-box">
                <input type="text" name="hobbies" placeholder="Enter Hobbies and Interests">
            </div>

            <h3>What are your hobbies and interests?</h3>
            <div class="text-box">
                <input type="text" name="interests" placeholder="Enter Hobbies and Interests">
            </div>

            <h3>Have you ever volunteered before?</h3>
            <div class="text-box">
                <input type="text"  name="volunteeredbefore" placeholder="Please tell us about any of your past volunteering experiences">
            </div>

            <h3>Where did you hear about us?</h3>
            <div class="text-box">
                <select name="how" required>
                    <option value="Social Media">Social Media</option>
                    <option value="Website">Website</option>
                    <option value="Friend/Family">Friend/Family</option>
                    <option value="Event">Event</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <h3>Which day can you work?</h3>
            <div class="text-box">
                <select name="workday" required> <!--multiple -->
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>

            <h3>Candidate Signature:</h3>
            <div class="text-box">
                <input type="file" name="signature" accept="image/*">
            </div>

            <div class="update-buttons">
        <input type="submit" value="submit">
        </div>
        </div>
    </form>
</body>
</html>
