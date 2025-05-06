<?php
// No need to include db.php here since saveproduct.php handles DB logic
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Ar's Flowers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8e44ad;
            --primary-light: #9b59b6;
            --primary-dark: #7d3c98;
            --secondary: #3498db;
            --secondary-light: #5dade2;
            --error: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --gray: #95a5a6;
            --light-gray: #bdc3c7;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .header i {
            font-size: 26px;
        }

        .form-container {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--white);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-light);
            transform: translateY(-2px);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error);
            border-left: 4px solid var(--error);
        }

        .alert i {
            font-size: 20px;
        }

        .image-upload {
            margin-bottom: 20px;
        }

        .image-preview {
            width: 180px;
            height: 180px;
            border: 2px dashed var(--light-gray);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 15px;
            transition: var(--transition);
            background-color: #f9f9f9;
            position: relative;
        }

        .image-preview:hover {
            border-color: var(--primary);
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            display: none;
            object-fit: cover;
        }

        .image-preview-text {
            color: var(--gray);
            font-size: 14px;
            text-align: center;
            padding: 0 15px;
        }

        .upload-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background-color: var(--light);
            border-radius: 6px;
            font-size: 14px;
            color: var(--dark);
            cursor: pointer;
            transition: var(--transition);
        }

        .upload-btn:hover {
            background-color: #e0e0e0;
        }

        .upload-btn i {
            color: var(--primary);
        }

        #image_upload {
            display: none;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px auto;
            }

            .form-container {
                padding: 20px;
            }

            .header h1 {
                font-size: 20px;
            }
        }

        /* Floating label animation */
        .floating-label-group {
            position: relative;
            margin-bottom: 20px;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 15px;
            top: 12px;
            transition: var(--transition);
            background: var(--white);
            padding: 0 5px;
            color: var(--gray);
            font-size: 14px;
        }

        .floating-input:focus~.floating-label,
        .floating-input:not(:placeholder-shown)~.floating-label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: var(--primary);
            background: var(--white);
        }

        .floating-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }

        .floating-input:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Add New Product</h1>
        </div>

        <div class="form-container">
            <!-- Status Message -->
            <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
                <div class="alert <?= $_GET['status'] === 'success' ? 'alert-success' : 'alert-error' ?>">
                    <i class="fas <?= $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <?php endif; ?>

            <!-- Product Add Form -->
            <form action="saveproduct.php" method="POST" enctype="multipart/form-data">
                <div class="form-group floating-label-group">
                    <input type="text" id="name" name="name" class="floating-input" placeholder=" " required minlength="2" maxlength="100">
                    <label class="floating-label">Product Name</label>
                </div>

                <div class="form-group floating-label-group">
                    <textarea id="description" name="description" class="floating-input" placeholder=" " maxlength="255" style="min-height: 80px;"></textarea>
                    <label class="floating-label">Description</label>
                </div>

                <div class="form-group floating-label-group">
                    <input type="number" id="price" name="price" class="floating-input" placeholder=" " step="0.01" min="0" required>
                    <label class="floating-label">Price (₱)</label>
                </div>

                <div class="form-group floating-label-group">
                    <input type="number" id="quantity" name="quantity" class="floating-input" placeholder=" " min="0" required>
                    <label class="floating-label">Stock Quantity</label>
                </div>

                <div class="form-group">
                    <label for="size">Size</label>
                    <select id="size" name="size" class="form-control" required>
                        <option value="" disabled selected>Select size</option>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                        <option value="Standard">Standard</option>
                    </select>
                </div>

                <div class="form-group floating-label-group">
                    <input type="text" id="color" name="color" class="floating-input" placeholder=" " required>
                    <label class="floating-label">Color</label>
                </div>

                <div class="form-group">
                    <label for="available">Availability</label>
                    <select id="available" name="available" class="form-control" required>
                        <option value="1">In Stock</option>
                        <option value="0">Out of Stock</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <div class="image-upload">
                        <div class="image-preview">
                            <img id="image-preview" src="#" alt="Image Preview">
                            <div class="image-preview-text">No image selected</div>
                        </div>
                        <label for="image_upload" class="upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Choose Image
                        </label>
                        <input type="file" id="image_upload" name="image_upload" accept="image/*">
                        <input type="hidden" id="image_url" name="image_url">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Add Product
                    </button>
                </div>
            </form>

            <div class="footer">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('image_upload').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const previewText = document.querySelector('.image-preview-text');
            const imageUrlInput = document.getElementById('image_url');

            if (e.target.files.length > 0) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                    previewText.style.display = 'none';

                    // Set the filename as the image_url value
                    imageUrlInput.value = e.target.files[0].name;
                };

                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.style.display = 'none';
                previewText.style.display = 'block';
                imageUrlInput.value = '';
            }
        });

        // Add floating label functionality to all floating inputs
        document.querySelectorAll('.floating-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('.floating-label').classList.add('active');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentNode.querySelector('.floating-label').classList.remove('active');
                }
            });

            // Initialize labels for pre-filled values
            if (input.value) {
                input.parentNode.querySelector('.floating-label').classList.add('active');
            }
        });
    </script>
</body>

</html>