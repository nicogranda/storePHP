<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Esta metaetiqueta especifica UTF-8 -->
    <title>E-mail</title>
    <?php include '../../config.php'; ?>
    <?php include '../../assets.php'; ?>
    <link rel="stylesheet" href="css/email.css" type="text/css" charset="utf-8" />
</head>

<body>
    
    <form action="mail.php" method="post" id="f1" name="f1" enctype="multipart/form-data" onsubmit="return validacion()">
        
        <div class='item' style="text-align:center;">
            <img src='../../images/logo.png' style='width:200px;padding:20px;'>
        </div>
        
        <!-- Sender's Email input -->
        <div class='item'>
            <input type="email" name="senderEmail" id="senderEmail" placeholder="Sender Email" value="contact@ikusa.net">
        </div>

        <!-- Email input -->
        <div class='item'>
            <input type="email" name="mailerTo" id="email" placeholder="Email">
        </div>
        <div id='alertemail' style='display:none;' class="alert">E-mail</div>

        <!-- Business input -->
        <div class='item'>
            <input type="text" name="business" id="business" placeholder="Business">
        </div>
        
        <!-- Representative input -->
        <div class='item'>
            <input type="text" name="representative" id="representative" placeholder="Representative">
        </div>
        
        <!-- Subject input -->
        <div class='item'>
            <input type="text" name="subject" id="subject" placeholder="Subject">
        </div>
        <div id='alertsubject' style='display:none;' class="alert">Subject</div>
        
        <!-- Executive as select -->
        <div class='item'>
            <select name="userID" id="executive">
                <?php
                // Database connection
                require('../../database.php');

                // Fetch executives from the database
                $sql = "SELECT * FROM users ORDER BY name, lastname";
                $result = $mysqli->query($sql);

                // Check if there are results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No executives found</option>";
                }

                $mysqli->close();
                ?>
            </select>
        </div>
        
        <!-- Campaign input -->
        <div class='item'>
            <input type="text" name="campaign" id="campaign" placeholder="Campaign">
        </div>
        <div id='campaign' style='display:none;' class="alert">Show campaign name</div>
        
        <!-- Message selector -->
        <div class='item'>
            <select name="message_id" id="message_id" onchange="toggleMessageTextarea()">
                <option value="">Select</option>
                <option value="11">Budget</option>
                <option value="12">Design</option>
                <option value="13">Print Artwork</option>
                <option value="14">Invoice</option>
                <option value="22">Income Receipt</option>
                <option value="30">Inquire on English</option>
                <option value="31">Budget Require</option>
                <option value="32">Information</option>
             
                
            </select>
        </div>
        <div id='alertdate' style='display:none;' class="alert">Show Message</div>
        
        <!-- Textarea without TinyMCE -->
        <div class='item' id="messageContainer" style="display:none;">
            <textarea name="message" id="message" rows="20" cols="50" placeholder="Enter your message here"></textarea>
        </div>
        <br>
        <!-- File input -->
        <div class='item'>
            <input type="file" name="archivos[]" multiple title="Attachment">
        </div>
        
        <!-- Submit button -->
        <div class='item'>
            <input type="submit" name="send" id="send" value="Send">
        </div>
    </form>

    <script>
        function toggleMessageTextarea() {
            const messageSelect = document.getElementById('message_id');
            const messageContainer = document.getElementById('messageContainer');

            if (messageSelect.value == "31" || messageSelect.value == "32" || messageSelect.value == "30") {
                messageContainer.style.display = "block";
            } else {
                messageContainer.style.display = "none";
            }

        }
    </script>
</body>
</html>
