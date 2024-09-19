<div class="container">
    <!--top cat-->
    <div class="row">
        <div class="col-md-12">
            <div class="shop-categories owl-carousel mt-5">
                <div class="item">
                    <a href="shop.php">
                        <div class="media d-flex align-items-center justify-content-center">
                            <span class="d-flex mr-2"><i class="sb-bistro-carrot"></i></span>
                            <div class="media-body">
                                <h5>All</h5>
                                <p>Freshly Harvested Veggies From Local Growers</p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php foreach ($categories as $cat): ?>
                    <div class="item">
                        <a href="shop.php?category=<?php echo htmlspecialchars($cat['Category']); ?>">
                            <div class="media d-flex align-items-center justify-content-center">
                                <?php
                                // Determine the icon and description based on the category
                                $icon = '';
                                $description = '';

                                switch ($cat['Category']) {
                                    case 'Vegetable':
                                        $icon = '<i class="sb-bistro-carrot"></i>';
                                        $description = 'Freshly Harvested Veggies From Local Growers';
                                        break;
                                    case 'Fruit':
                                        $icon = '<i class="sb-bistro-apple"></i>';
                                        $description = 'Variety of Fruits From Local Growers';
                                        break;
                                    case 'Meat':
                                    case 'Fish':
                                        $icon = '<i class="sb-bistro-roast-leg"></i>'; // Default icon for Meat and Fish
                                        $description = 'Protein Rich Ingredients From Local Farmers';
                                        break;
                                    default:
                                        // Randomly select one of the two icons for other categories
                                        $icons = ['sb-bistro-appetizer', 'sb-bistro-french-fries'];
                                        $icon = '<i class="' . $icons[array_rand($icons)] . '"></i>';
                                        $description = 'Lets try some fresh';
                                        break;
                                }
                                ?>

                                <span class="d-flex mr-2"><?php echo $icon; ?></span>
                                <div class="media-body">
                                    <h5><?php echo htmlspecialchars($cat['Category']); ?></h5>
                                    <p><?php echo htmlspecialchars($description); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>


            </div>
        </div>
    </div>

    <!-- Container for the search form and filter button -->
    <!--<div class="container" >-->
    <div class="row" style="margin-top:20px;">
        <!-- Search Form -->
        <div class="col-md-11">
            <div class="search-container">
                <form class="d-flex mb-3" method="get" action="shop.php">
                    <input class="form-control search-input" type="search" name="search" placeholder="Search by name or category" aria-label="Search" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                    <i class="bi bi-x cancel-icon" id="cancelIcon" style="margin-top: 5px; display: none; color: red;cursor: pointer;"></i>
                    <button class="btn btn-outline-info search-btn" type="submit">Search</button>
                </form>
            </div>
        </div>

        <!-- Filter Button with Bootstrap Icon -->
        <div class="col-md-1 d-flex align-items-center">
            <button class="btn btn-info mb-3 w-100" id="filterButton">
                <i class="bi bi-funnel"></i> 
            </button>
        </div>

    </div>

    <!-- Filter Drawer -->
    <div class="drawer" id="filterDrawer" style="display:none; position: fixed; right: 0; top: 0; width: 300px; height: 100%; background-color: #f8f9fa; border-left: 1px solid #ddd; box-shadow: -2px 0 5px rgba(0,0,0,0.1); z-index: 1050; overflow-y: auto;">
        <div class="p-3">
            <h4 style="margin-top: 30px;">Filter Products</h4>
            <form method="get" action="" style="margin-top: 30px;">
                <!-- Category Filter -->
                <div class="mb-3">
                    <!--                    <label for="category" class="form-label">Category</label>
                                        <select id="category" name="category" class="form-select">
                                            <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo htmlspecialchars($cat['Category']); ?>" <?php echo $cat['Category'] == $category ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['Category']); ?>
                                                        </option>
                    <?php endforeach; ?>
                                        </select>-->

                    <div class="form-group">
                        <label for="category" class="form-label">Select Category</label>
                        <select id="category" name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['Category']); ?>" <?php echo $cat['Category'] == $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['Category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                </div>

                <!-- Price Range Filter -->
                <div class="mb-3">
                    <label for="priceMin" class="form-label">Price Range</label>
                    <input type="number" id="priceMin" name="priceMin" class="form-control" placeholder="Min Price" value="<?php echo htmlspecialchars($priceMin); ?>">
                    <input type="number" id="priceMax" name="priceMax" class="form-control mt-2" placeholder="Max Price" value="<?php echo htmlspecialchars($priceMax); ?>">
                </div>

                <!-- Weight Range Filter -->
                <div class="mb-3">
                    <label for="weightMin" class="form-label">Weight Range</label>
                    <input type="number" id="weightMin" name="weightMin" class="form-control" placeholder="Min Weight (kg)" value="<?php echo htmlspecialchars($weightMin); ?>">
                    <input type="number" id="weightMax" name="weightMax" class="form-control mt-2" placeholder="Max Weight (kg)" value="<?php echo htmlspecialchars($weightMax); ?>">
                </div>

                <!-- Availability Filter -->
                <!--                <div class="mb-3">
                                    <label for="availability" class="form-label">Availability</label>
                                    <select id="availability" name="availability" class="form-select">
                                        <option value="">All</option>
                                        <option value="1" <?php echo $availability == '1' ? 'selected' : ''; ?>>Available</option>
                                        <option value="0" <?php echo $availability == '0' ? 'selected' : ''; ?>>Unavailable</option>
                                    </select>
                                </div>-->

                <!-- Buttons -->
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Apply Filters</button>
                <button type="button" class="btn btn-secondary ms-2" id="closeFilter" style="margin-top: 10px;">Close</button>
            </form>
        </div>
    </div>

</div>

<script>
    // Handle cancel icon click
    document.getElementById('cancelIcon').addEventListener('click', function () {
        var searchInput = document.querySelector('.search-input');
        searchInput.value = ''; // Clear the input field
        searchInput.focus(); // Optional: focus on the input field after clearing
    });

    // Handle input event to show/hide the cancel icon
    document.querySelector('.search-input').addEventListener('input', function () {
        var cancelIcon = document.getElementById('cancelIcon');
        cancelIcon.style.display = this.value ? 'block' : 'none';
    });

    //filter drawer
    document.getElementById('filterButton').addEventListener('click', function () {
        document.getElementById('filterDrawer').style.display = 'block';
    });

    document.getElementById('closeFilter').addEventListener('click', function () {
        document.getElementById('filterDrawer').style.display = 'none';
    });

    document.getElementById('filterButton').addEventListener('click', function () {
        document.getElementById('filterDrawer').classList.add('drawer-open');
    });

    document.getElementById('closeFilter').addEventListener('click', function () {
        document.getElementById('filterDrawer').classList.remove('drawer-open');
    });

</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
