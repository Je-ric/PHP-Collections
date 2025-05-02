<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="../../assets/src/Boxicon/boxicons-2.1.4/css/boxicons.min.css">
	<link rel="stylesheet" href="../../assets/css/sidebar.css">
	<link rel="stylesheet" href="../../assets/css/modal.css">
    <style>
        body::-webkit-scrollbar {
            width: 8px;
        }

        body::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        body::-webkit-scrollbar-thumb {
            background: black; 
            border-radius: 10px;
        }
    </style>
	<title>Urban Grail</title>
</head>
<body>


	<!-- <section id="sidebar" class="hide"> -->
	<section id="sidebar">
		<a href="../reports/index.php" class="brand">
			<img src="../../assets/src/images/Logo.jpg" alt="logo" class="logo">
			<span class="text">Urban Grail</span>
		</a>
		<ul class="side-menu top">
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/reports/') !== false ? 'active' : '' ?>">
        <a href="../reports/index.php">
            <i class='bx bxs-dashboard'></i>
            <span class="text">Dashboard</span>
        </a>
    </li>
    <?php if (!isset($_SESSION['log_emp'])){ ?>
        <li class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">
            <a href="../inventory/products.php">
                <i class='bx bxs-package'></i>
                <span class="text">Inventory</span>
            </a>
        </li>
    <?php } ?>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : '' ?>">
        <a href="../reports/history.php">
            <i class='bx bxs-archive-in'></i>
            <span class="text">History & Archives</span>
        </a>
    </li>
    <li class="<?= in_array(basename($_SERVER['PHP_SELF']), ['pos.php', 'payment.php', 'receipt.php']) && strpos($_SERVER['REQUEST_URI'], '/pos/') !== false ? 'active' : '' ?>">
        <a href="../pos/pos.php">
            <i class='bx bxs-cart-alt'></i>
            <span class="text">POS</span>
        </a>
    </li>
    <?php if (!isset($_SESSION['log_emp'])){ ?>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'accounts.php' ? 'active' : '' ?>">
        <a href="../accounts/accounts.php">
            <i class="bx bxs-user"></i>
            <span class="text">Accounts</span>
        </a>
    </li>
    <?php } ?>
    <?php #if (!isset($_SESSION['log_emp'])){ ?>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : '' ?>">
        <a href="../accounts/attendance.php">
            <i class='bx bxs-calendar'></i>
            <span class="text">Attendance</span>
        </a>
    </li>
    <?php #} ?>
</ul>
		<ul class="side-menu">
        <?php if (!isset($_SESSION['log_emp'])){ ?>
			<li>
				<a href="../pos/settings.php">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
            <?php } ?>
			<!-- <li>
				<a href="#" class="logout" onclick="openLogoutModal()">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li> -->
		</ul>
	</section>

	<div id="logoutModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLogoutModal()">&times;</span>
            <h2>Logout Confirmation</h2><br>
            <p>Are you sure you want to log out?</p><br>
            <form method="POST" action="../../includes/logout.php">
                <input type="hidden" name="action" value="logout"> 
                <input type="submit" class="button-confirm" name="logout" value="Yes, log out">
                <button type="button" class="button-cancel" onclick="closeLogoutModal()">Cancel</button>
            </form>
        </div>
    </div>

	<section id="content">
		<nav>
			<i class='bx bx-menu' ></i>
            <h2>
                <?php   
                $currentPage = basename($_SERVER['PHP_SELF']);
                
                $pageTitles = [
                    'index.php' => 'Dashboard',
                    'products.php' => 'Products & Categories',
                    'history.php' => 'History & Archives',
                    'accounts.php' => 'Accounts',
                    'attendance.php' => 'Attendance',
                    'pos.php' => 'Point of Sale',
                    'payment.php' => 'Payment',
                    'receipt.php' => 'Receipt',
                    'settings.php' => 'Settings',
                    'attendance_history.php' => 'Attendance History',
                    'receipt_details.php' => 'Receipt Details',
                    'promo.php' => 'Promotions',

                ];

                echo isset($pageTitles[$currentPage]) ? $pageTitles[$currentPage] : ucfirst(str_replace('.php', '', $currentPage));
                ?>
            </h2>

            <div class="header-user-info">
                <?php 
                    $userId = $_SESSION['user_id'];
                    $userResult = $conn->query("SELECT * FROM Users WHERE user_id = '$userId'");
                    $userInfo = $userResult->fetch_assoc();
                ?>
                <?php if (!empty($userInfo['user_image'])): ?>
                    <img src="<?= htmlspecialchars($userInfo['user_image']) ?>" alt="User Image" class="header-user-avatar">
                <?php else: ?>
                    <div class="user-avatar-default"><?= strtoupper(substr($userInfo['name'], 0, 1)) ?></div>
                <?php endif; ?>
                <div class="header-user">
                    <p class="header-user-name"><?= htmlspecialchars($userInfo['name']) ?></p>
                    <p class="header-user-email"><?= htmlspecialchars($userInfo['email']) ?></p>
                </div>

                <div class="view-profile-popup">
                    <a href="../accounts/profile.php?user_id=<?= $_SESSION['user_id'] ?>"><i class='bx bx-user'></i>View Profile</a>
                    <a href="#" class="logout" onclick="openLogoutModal()"><i class='bx bxs-log-out-circle' ></i><span class="text">Logout</span>
				</a>
                </div>
            </div>
			
		</nav>
		
	<!-- </section>  -->
	<!-- Pag tinanggal ko toh, mag-ooverlap yung sidebar -->

	<script>
		    function openLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                closeLogoutModal();
            }
        }


		
        const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');
		allSideMenu.forEach(item=> {
			const li = item.parentElement;

			item.addEventListener('click', function () {
				allSideMenu.forEach(i=> {
					i.parentElement.classList.remove('active');
				})
				li.classList.add('active');
			})
		});




// const menuBar = document.querySelector('#content nav .bx.bx-menu');
// const sidebar = document.getElementById('sidebar');

// if (localStorage.getItem('sidebarState') === 'hidden') {
//     sidebar.classList.add('hide');
// } else {
//     sidebar.classList.remove('hide');
// }

// menuBar.addEventListener('click', function () {
//     sidebar.classList.toggle('hide');
//     if (sidebar.classList.contains('hide')) {
//         localStorage.setItem('sidebarState', 'hidden');
//     } else {
//         localStorage.setItem('sidebarState', 'visible');
//     }
// });

// window.addEventListener('resize', function () {
//     if (window.innerWidth < 768) {
//         sidebar.classList.add('hide');
//         localStorage.setItem('sidebarState', 'hidden');
//     } else {
//         sidebar.classList.remove('hide');
//         localStorage.setItem('sidebarState', 'visible');
//     }
// });



const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');


const currentPage = window.location.pathname.split('/').pop();


if (localStorage.getItem('sidebarState') === 'hidden' && 
    (currentPage !== 'pos.php' && currentPage !== 'payment.php' && currentPage !== 'receipt.php')) {
    sidebar.classList.add('hide');
} else {
    sidebar.classList.remove('hide');
}


if (currentPage === 'pos.php') {
    sidebar.classList.add('hide'); 
    localStorage.setItem('sidebarState', 'hidden'); 

    
    menuBar.style.pointerEvents = 'none'; 
    menuBar.style.opacity = '0.5'; 
} else {
    
    menuBar.addEventListener('click', function () {
        sidebar.classList.toggle('hide');
        if (sidebar.classList.contains('hide')) {
            localStorage.setItem('sidebarState', 'hidden');
        } else {
            localStorage.setItem('sidebarState', 'visible');
        }
    });
}


window.addEventListener('resize', function () {
    if (window.innerWidth <= 768) {
        sidebar.classList.add('hide');
        localStorage.setItem('sidebarState', 'hidden');
    } else if (currentPage !== 'pos.php') {
        sidebar.classList.remove('hide');
        localStorage.setItem('sidebarState', 'visible');
    }
});


window.addEventListener('beforeunload', function() {
    if (currentPage === 'pos.php') {
        localStorage.setItem('sidebarState', 'visible');
    }
});

    </script>

	
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>