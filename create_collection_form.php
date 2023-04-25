<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Collection - Movies Collection</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="page-header">
    <div class="container">
        <div class="logo">
            <a href="/"><img src="logo.png" alt="Movies Collection"></a>
        </div>
        <nav class="navbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">My Collections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Favorites</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Log In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Register</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="container mt-3">
    <h1 class="mt-3 mb-3">Create Collection</h1>
    <form method="post" action="create_collection.php">
        <div class="form-group">
            <label for="collection-name">Collection Name:</label>
            <input type="text" class="form-control" id="collection-name" name="collection_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<script src="script.js"></script>
</body>
</html>
