<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sidebar Menu for Admin Dashboard | CodingNepal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    .sidebar {
      position: fixed;
      height: 100%;
      width: 200px;
      margin-top: 40px;
      background: #11101d;
      padding: 15px;
      z-index: 99;
      font-family: "Poppins", sans-serif;
    }
    
    .sidebar a {
      color: #fff;
      text-decoration: none;
    }
    
    .menu-content {
      margin-top: 20px;
      overflow-y: auto;
    }
    
    .menu-content::-webkit-scrollbar {
      display: none;
    }
    
    .menu-items {
      list-style: none;
    }
    
    .submenu {
      display: none;
      list-style: none;
      padding-left: 20px;
    }
    
    .submenu-active {
      display: block;
    }
    
    .item a,
    .submenu-item a {
      padding: 16px;
      display: block;
      width: 100%;
      border-radius: 12px;
      transition: background 0.3s ease;
    }
    
    .item a:hover,
    .submenu-item a:hover,
    .submenu .menu-title:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .active {
      background: rgba(255, 255, 255, 0.2);
    }
    
    .menu-title {
      padding: 16px;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      color: #fff;
    }
    
    .menu-title i {
      margin-right: 10px;
    }
    
    .main {
      margin-left: 260px;
      width: calc(100% - 260px);
      transition: all 0.5s ease;
      z-index: 1000;
    }
  </style>
</head>
<body>
  <nav class="sidebar">
    <div class="menu-content">
      <ul class="menu-items">
        <li class="item">
          <a href="index.php?page=home" class="home"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="item submenu-item">
          <a href="#" class="rooms"><i class="fa fa-home"></i> Rooms</a>
          <ul class="submenu">
            <li class="item">
              <a href="index.php?page=add_room" class="add_room"><i class="fa fa-plus-circle"></i> Add Room</a>
            </li>
            <li class="item">
              <a href="index.php?page=view_room" class="view_room"><i class="fas fa-bed"></i> View Room</a>
            </li>
          </ul>
        </li>
        <li class="item">
          <a href="index.php?page=tenants" class="tenants"><i class="fa fa-user-friends"></i> Tenants</a>
        </li>
        <li class="item">
          <a href="index.php?page=invoices" class="invoices"><i class="fa fa-file-invoice"></i> Payments</a>
        </li>
        <li class="item">
          <a href="index.php?page=balance_report" class="balance_report"><i class="fa fa-list-alt"></i> Outstanding Balance</a>
        </li>
        <li class="item">
          <a href="index.php?page=payment_report" class="payment_report"><i class=" fa fa-file-alt"></i> Income Report</a>
        </li>
        <li class="item">
          <a href="index.php?page=email" class="email"><i class="fa fa-envelope"></i> Send Email</a>
        </li>
        <li class="item">
          <a href="index.php?page=users" class="users"><i class="fa fa-users"></i> Users</a>
        </li>
      </ul>
    </div>
  </nav>
  <nav class="navbar">
    <i class="fa-solid fa-bars" id="sidebar-close"></i>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sidebar = document.querySelector(".sidebar");
      const sidebarClose = document.querySelector("#sidebar-close");
      const submenuItems = document.querySelectorAll(".submenu-item > a");
      const submenus = document.querySelectorAll(".submenu");
      const links = document.querySelectorAll('.menu-content a');

      sidebarClose.addEventListener("click", () => sidebar.classList.toggle("close"));

      submenuItems.forEach(item => {
        item.addEventListener("click", (e) => {
          e.preventDefault();
          const submenu = item.nextElementSibling;
          submenu.classList.toggle("submenu-active");

          // Collapse other submenus
          submenus.forEach(sub => {
            if (sub !== submenu) {
              sub.classList.remove("submenu-active");
            }
          });
        });
      });

      links.forEach(link => {
        link.addEventListener('click', () => {
          links.forEach(link => link.classList.remove('active'));
          link.classList.add('active');
        });
      });

      // Highlight active link based on URL and open submenu if necessary
      const currentPage = window.location.search.split('page=')[1];
      if (currentPage) {
        const activeLink = document.querySelector(`a.${currentPage}`);
        if (activeLink) {
          activeLink.classList.add('active');
          const parentSubmenu = activeLink.closest('.submenu');
          if (parentSubmenu) {
            parentSubmenu.classList.add('submenu-active');
          }
        }
      }
    });
  </script>
</body>
</html>