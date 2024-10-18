<?php 
session_start();
include('config/conn.php');
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 1;

// Query untuk mengambil data dari tabel tourismcategories
$category_query = "SELECT category_name, title_categories, desc_categories, background_image FROM tourismcategories WHERE category_id = ?";
$stmt = $con->prepare($category_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$stmt->bind_result($category_name, $title_categories, $desc_categories, $background_image);
$stmt->fetch();
$stmt->close();

// Query untuk mengambil data kota dan jumlah tour dari tabel cities dan tourismplaces
$city_query = "
    SELECT c.city_id, c.city_name, MIN(t.image_url) AS image_url, COUNT(t.tourism_id) AS tour_count
    FROM cities c
    LEFT JOIN tourismplaces t ON c.city_id = t.city_id
    LEFT JOIN tourismcategories tc ON t.category_id = tc.category_id
    WHERE tc.category_id = ?
    GROUP BY c.city_id, c.city_name
";
$stmt = $con->prepare($city_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$stmt->bind_result($city_id, $city_name, $image_url, $tour_count);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <title>WikiTrip</title>
</head>

<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main-nav">
        <div class="container">
            <a class="navbar-brand logo fw-bold fs-4 d-flex align-items-center" href="#page-top">
                <img src="image/logo_wikitrip.png" alt="Logo" class="logo-img me-2">
                <span class="text-logo1">WIKI</span><span class="text-logo2">TRIP</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#About">About</a>
                    </li>
                    <!-- Destination Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Destination
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="nature.html">Nature destinations</a></li>
                            <li><a class="dropdown-item" href="#Cultural">Cultural destinations</a></li>
                            <li><a class="dropdown-item" href="#Culinary">Culinary destinations</a></li>
                        </ul>
                    </li>
                    <!-- Event Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownEvent" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Events
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownEvent">
                            <li><a class="dropdown-item" href="#Music">Music Events</a></li>
                            <li><a class="dropdown-item" href="#Culinary">Culinary events</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#Community">Community</a>
                    </li>
                </ul>
                <div>
                    <ul>
                        <!-- Profile Dropdown -->
                        <li class="nav-item profile-dropdown">
                            <div class="bi bi-person-circle text-white fs-4 me-2"></div>
                            <ul>
                                <li class="sub-item">
                                    <i class="bi bi-chat-heart material-icons-outlined"></i>
                                    <p>Activity</p>
                                </li>
                                <li class="sub-item">
                                    <i class="bi bi-bookmark material-icons-outlined"></i>
                                    <p>Bookmark</p>
                                </li>
                                <li class="sub-item">
                                    <i class="bi bi-box-arrow-left material-icons-outlined"></i>
                                    <p>Logout</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="main_background">
        <img src="<?php echo $background_image; ?>" alt="Beautiful nature background" class="mainback_img">
        <div class="home__container container">
            <div class="home__data">
                <span class="home__data-subtitle">Discover your place</span>
                <h1 class="home__data-title">Explore The <br> Best <b><?php echo strtoupper($category_name); ?><br> DESTINATIONS</b></h1>
                <a href="#explore" class="button">Explore</a>
            </div>
        </div>
    </div>

    <!-- Section Content Title and Description -->
    <div class="main-content">
        <h2 class="content-title"><?php echo $title_categories; ?></h2>
        <p class="content-description"><?php echo $desc_categories; ?></p>
        <hr class="content-divider">

        <!-- Section Explore Title -->
        <h3 class="explore-title" id="explore">Explore <?php echo $category_name; ?> by City or Regency</h3>

        <!-- Discover Grid for Cities -->
        <div class="discover__grid">
            <?php while ($stmt->fetch()): ?>
                <?php $image_url = $image_url ? $image_url : 'image/default.jpg'; ?>
                <a href="asahan_nature.html?city_id=<?php echo $city_id; ?>" class="discover__card">
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $city_name; ?>" class="discover__img">
                    <div class="discover__data">
                        <h2 class="discover__title"><?php echo $city_name; ?></h2>
                        <span class="discover__description"><?php echo $tour_count; ?> tours available</span>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
        <button class="load-more" id="loadMoreBtn">Load More</button>
    </div>
    <script src="js/nature.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>