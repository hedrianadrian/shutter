<?php
$msg = "";
$db = mysqli_connect("localhost", "root", "", "gallery");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['upload'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = __DIR__ . "/image/" . $filename; // Relative path to the destination folder

    $sql = 'INSERT INTO image (filename) VALUES (\'' . $filename . '\')';

    if (mysqli_query($db, $sql)) {
        if (move_uploaded_file($tempname, $folder)) {
            echo "<h3>Image uploaded successfully!</h3>";
        } else {
            echo "<h3>Failed to move the uploaded image!</h3>";
            echo "<p>Error: " . $_FILES["uploadfile"]["error"] . "</p>";
            echo "<p>Destination: " . $folder . "</p>";
        }
    } else {
        echo "<h3>Failed to insert data into the database!</h3>";
        echo "<p>Error: " . mysqli_error($db) . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shutter Wave</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-image: url('https://imageupload.io/ib/AkAYmHWcdMrAw3N_1700577877.png');
        background-image: url('bg.png');
    background-size: cover; /* Adjust the background size to cover the entire body */
    background-position: center; /* Adjust the background position to center it */
    background-repeat: no-repeat; /* Ensure the background doesn't repeat */
    background-attachment: fixed; /* Make the background fixed */
    
    margin: 0; /* Remove default body margin */
    height: 100vh; /* Ensure full height */
    font-family: Arial, sans-serif;
    text-align: center;
    }

    #content {
    width: 50%;
    margin: 20px auto;
    border: 1px solid #ccc;
    padding: 20px;
    color: white;
    text-align: center;
    background-image: url('https://imageupload.io/ib/CXuK9XCvtqfquPl_1700579274.png');
    background-image: url('bg2.png');
    background-size: cover; /* Adjust the background size to cover the entire body */
    background-position: center; /* Adjust the background position to center it */
    background-repeat: no-repeat; /* Ensure the background doesn't repeat */
    background-attachment: fixed; /* Make the background fixed */
    
}

/* Form styling with neon aesthetic */
form {
    margin: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.btn-primary {
    background-color: #2abfc4; /* Neon blue background color */
    color: #fff;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
    transition: background-color 0.3s;
}

/* Neon glow effect on hover */
.btn-primary:hover {
    background-color: #19a2a8; /* Darker shade for hover effect */
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.8);
}

#display-image {
    margin-top: 20px;
    text-align: center; /* Center the content horizontally */
    padding-bottom: 30px;
}

.carousel {
    width: 50%;
    margin: 20px auto;
    overflow: hidden;
    text-align: center; /* Center the content horizontally */
    padding-bottom: 30px;
}

.carousel img {
    width: 50%;
    height: auto;
    display: none;
    margin: 0 auto; /* Center the images within the .carousel */
    padding-bottom: 30px;
}

h1.neon {
    font-family: Tahoma;
    padding-top: 20px;
    font-size: 3em;
    color: #2abfc4;
    text-align: center;
    margin-top: 60px;
    text-shadow: 0 0 10px rgba(255, 0, 234, 0.8),
                 0 0 10px rgba(255, 0, 234, 0.8),
                 0 0 30px rgba(0, 255, 255, 0.8),
                 0 0 40px rgba(0, 255, 255, 0.8),
                 0 0 50px rgba(0, 255, 255, 0.8);
}

h3{
        font-family: Verdana;
        padding-top: 50px;
        color: white;
        padding-bottom: -20px;
    }

</style>
</head>

<body>
    <h1 class="neon">Shutter Wave</h1>
    <div id="content">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <input class="form-control" type="file" name="uploadfile" value="" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" name="upload">Upload Image</button>
            </div>
        </form>
    </div>

    <div id="display-image">
        <div id="imageGallery" class="carousel">
            <!-- Images will be displayed here -->
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentSlideIndex = 1;

        fetchImages();

        function fetchImages() {
            fetch('get_images.php')
                .then(response => response.json())
                .then(data => displayImages(data))
                .catch(error => console.error('Error fetching images:', error));
        }

        function displayImages(images) {
            const gallery = document.getElementById('imageGallery');
            gallery.innerHTML = '';

            images.forEach((image, index) => {
                const imgElement = document.createElement('img');
                imgElement.src = './image/' + image.filename; // Update the path based on your folder structure
                imgElement.style.display = index === 0 ? 'block' : 'none';
                gallery.appendChild(imgElement);
            });

            console.log('Images:', images);

            // Add navigation buttons
            const prevButton = document.createElement('button');
            prevButton.innerHTML = 'Previous';
            prevButton.addEventListener('click', () => showSlides(currentSlideIndex - 1));
            gallery.appendChild(prevButton);

            const nextButton = document.createElement('button');
            nextButton.innerHTML = 'Next';
            nextButton.addEventListener('click', () => showSlides(currentSlideIndex + 1));
            gallery.appendChild(nextButton);
        }

        function showSlides(index) {
            const slides = document.querySelectorAll('#imageGallery img');
            if (slides.length > 0) {
                if (index > slides.length) { index = 1 }
                if (index < 1) { index = slides.length }

                slides.forEach(slide => slide.style.display = 'none');
                slides[index - 1].style.display = 'block';

                currentSlideIndex = index;
            }
        }
    });
</script>
</body>
</html>