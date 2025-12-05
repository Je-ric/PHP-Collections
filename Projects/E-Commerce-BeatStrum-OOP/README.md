# BeatStrum - E-Commerce Platform for Musical Instruments

## 📋 Description

**BeatStrum** is a comprehensive e-commerce web application built with PHP using Object-Oriented Programming (OOP) principles. The platform specializes in selling musical instruments and accessories, providing a seamless shopping experience for customers and a robust management system for administrators.

The application features a dual-interface system: a customer-facing frontend for browsing and purchasing musical instruments, and an administrative backend for managing products, categories, orders, and inventory.

## 🎯 Purpose

The primary purpose of this project is to demonstrate:
- **E-commerce functionality** - Complete online shopping experience with cart, checkout, and order management
- **Object-Oriented PHP** - Implementation of OOP principles including classes, methods, and encapsulation
- **Database management** - Efficient MySQL database design with proper relationships and constraints
- **User authentication** - Secure login system for both customers and administrators
- **Admin dashboard** - Comprehensive product and order management interface

## ✨ Features

### Customer Features (Client-Side)

#### 🛍️ Shopping Experience
- **Product Browsing** - Browse through a wide catalog of musical instruments
- **Category Navigation** - Filter products by categories (Guitars, Drums, Keyboards, Wind Instruments, etc.)
- **Product Search** - Real-time search functionality to find specific items
- **Product Sorting** - Sort products by:
  - Name (A-Z / Z-A)
  - Price (Low to High / High to Low)
  - Most Sold
- **Product Details** - View detailed information including:
  - Product images
  - Descriptions
  - Pricing
  - Stock availability
  - Shipping fees
  - Sales statistics

#### 🛒 Shopping Cart & Orders
- **Shopping Cart** - Add items to cart with quantity management
- **Order Management** - Track orders through different stages:
  - **Cart** - Items pending checkout
  - **To Ship** - Orders approved and ready for shipping
  - **To Receive** - Orders in transit
  - **Received Items** - Completed orders
- **Order History** - View past purchases and order status
- **Quantity Control** - Adjust item quantities in cart

#### 👤 User Account
- **User Registration** - Create new customer accounts
- **User Login/Logout** - Secure authentication system
- **Profile Management** - Manage personal information (name, age, phone, address)

### Administrator Features (Admin-Side)

#### 📦 Product Management
- **Add Products** - Create new product listings with:
  - Product name and description
  - Pricing and shipping fees
  - Product images
  - Stock quantity
  - Category assignment
- **Update Products** - Edit existing product information
- **Delete Products** - Remove products individually or in bulk
- **Product Search** - Quick search functionality
- **Product Sorting** - Sort by name, price, or sales
- **Stock Management** - Monitor inventory levels and availability status

#### 📁 Category Management
- **Add Categories** - Create new product categories
- **Update Categories** - Modify category names and images
- **Delete Categories** - Remove categories (with protection for default categories)
- **Category Images** - Upload and manage category icons

#### 📋 Order Management
- **View All Orders** - Monitor all customer orders
- **Order Status Management** - Update order status:
  - Pending
  - Approved
  - Shipped
  - Received
  - Cancelled
- **Order Details** - View complete order information including:
  - Customer details
  - Items ordered
  - Quantities
  - Total prices
  - Shipping fees
- **Bulk Operations** - Process multiple orders efficiently

#### 📊 Dashboard
- **Product Overview** - View all products with pagination
- **Sales Statistics** - Track total items sold per product
- **Inventory Status** - Monitor stock levels and availability
- **Search & Filter** - Quick access to products and orders

## 🛠️ Technologies Used

- **Backend**: PHP 8.2+ (Object-Oriented Programming)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Boxicons
- **Server**: XAMPP (Apache, MySQL, PHP)

## 📁 Project Structure

```
E-Commerce-BeatStrum-OOP/
│
├── admin/                    # Admin panel files
│   ├── add_item.php         # Add new products
│   ├── admin.php            # Admin login page
│   ├── config.php           # Database configuration
│   ├── delete_item.php      # Delete products
│   ├── delete_selected_items.php  # Bulk delete
│   ├── header.php           # Admin header/navigation
│   ├── index.php            # Admin dashboard
│   ├── logout.php           # Admin logout
│   ├── manage_category.php  # Category management
│   ├── manage_orders.php    # Order management
│   ├── session.php          # Session management
│   └── update_item.php      # Update products
│
├── client/                   # Customer-facing files
│   ├── config.php           # Database configuration
│   ├── index.php            # Product listing page
│   ├── item_category.php    # Category filtering
│   ├── login.php            # Customer login
│   ├── register.php         # Customer registration
│   ├── logout.php           # Customer logout
│   ├── search.php           # Search functionality
│   ├── shopping_cart.php   # Shopping cart main page
│   ├── tabCart.php          # Cart items tab
│   ├── tabShip.php          # To ship orders tab
│   ├── tabReceive.php       # To receive orders tab
│   ├── tabSuccessful.php    # Received orders tab
│   ├── user_details.php     # User profile
│   ├── view_item.php        # Product details page
│   └── 1.header.php         # Client header/navigation
│
├── css/                      # Stylesheets
│   ├── admin_form.css
│   ├── cart.css
│   ├── index_client.css
│   ├── index.css
│   ├── item_category.css
│   ├── login_client.css
│   ├── manage_category.css
│   ├── manage_orders.css
│   ├── register_client.css
│   └── view_item.css
│
├── script/                   # JavaScript files
│   ├── quantityButton.js
│   ├── search_index.js
│   ├── shopping_cartTab.js
│   └── tabCart.js
│
├── images/                   # Image assets
│   ├── BeatStrum-Logo.svg
│   ├── BeatStrum.png
│   └── [other images]
│
├── uploads/                  # Product images
├── category_uploads/         # Category images
├── sql/                      # Database files
│   └── ecommerce.sql        # Database schema and sample data
│
└── README.md                 # Project documentation
```

## 🗄️ Database Structure

The application uses a MySQL database named `ecommerce` with the following main tables:

### Core Tables

- **`admin_accounts`** - Administrator login credentials
- **`client_accounts`** - Customer account information
- **`categories`** - Product categories
- **`items`** - Product information
- **`item_categories`** - Many-to-many relationship between items and categories
- **`shopping_cart`** - Customer shopping cart items
- **`orders`** - Order records
- **`order_items`** - Individual items within orders
- **`item_reviews`** - Product reviews and ratings

### Key Relationships

- Items can belong to multiple categories
- Orders contain multiple order items
- Shopping cart items are linked to customers
- Reviews are associated with items and customers

## 🚀 Installation & Setup

### Prerequisites

- XAMPP (or similar PHP development environment)
- PHP 8.2 or higher
- MySQL/MariaDB
- Web browser

### Installation Steps

1. **Clone or Download the Project**
   ```bash
   # Place the project in your XAMPP htdocs directory
   C:\xampp\htdocs\PHP-Collections\Projects\E-Commerce-BeatStrum-OOP
   ```

2. **Database Setup**
   - Start XAMPP and ensure MySQL is running
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `ecommerce`
   - Import the SQL file: `sql/ecommerce.sql`
   - This will create all tables and insert sample data

3. **Database Configuration**
   - Update database credentials in:
     - `admin/config.php`
     - `client/config.php`
   - Modify if needed:
     ```php
     $host = "localhost";
     $username = "root";
     $password = "";
     $database = "ecommerce";
     ```

4. **File Permissions**
   - Ensure `uploads/` and `category_uploads/` directories are writable
   - These directories store product and category images

5. **Access the Application**
   - **Customer Interface**: `http://localhost/PHP-Collections/Projects/E-Commerce-BeatStrum-OOP/client/`
   - **Admin Interface**: `http://localhost/PHP-Collections/Projects/E-Commerce-BeatStrum-OOP/admin/`

### Default Credentials

**Admin Account:**
- Username: `admin`
- Password: `adminpassword`

**Sample Customer Account:**
- Username: `user`
- Password: `password`

*Note: Change default passwords in production environment*

## 💻 Usage

### For Customers

1. **Register/Login** - Create an account or login with existing credentials
2. **Browse Products** - Explore the catalog, use search or filter by category
3. **View Product Details** - Click on any product to see detailed information
4. **Add to Cart** - Select quantity and add items to shopping cart
5. **Checkout** - Review cart items and place order
6. **Track Orders** - Monitor order status in the shopping cart page

### For Administrators

1. **Login** - Access admin panel with admin credentials
2. **Manage Products** - Add, edit, or delete products from the dashboard
3. **Manage Categories** - Organize products into categories
4. **Process Orders** - Update order statuses (Approve, Ship, Mark as Received)
5. **Monitor Inventory** - Track stock levels and sales statistics

## 🎨 Key Functionalities

### Object-Oriented Design

The project demonstrates OOP principles through several classes:

- **`ItemManager`** - Handles product retrieval, search, and sorting
- **`CategoryManager`** - Manages category operations (add, update, delete)
- **`OrderHandler`** - Processes order status updates
- **`Database`** - Database connection management
- **`SessionManager`** - Session handling and authentication

### Security Features

- Session-based authentication
- SQL injection prevention (prepared statements)
- Password hashing
- Access control for admin and client areas

### User Experience Features

- Responsive design elements
- Real-time search suggestions
- Pagination for large product lists
- Tabbed interface for order management
- Visual feedback for stock status
- Intuitive navigation

## 📝 Notes

- The project uses absolute paths in some CSS includes - adjust paths according to your server configuration
- Image uploads are stored in `uploads/` and `category_uploads/` directories
- The database includes sample products and categories for testing
- Order status workflow: Pending → Approved → Shipped → Received

## 🔮 Future Enhancements

Potential improvements for the project:

- Payment gateway integration
- Email notifications for orders
- Product reviews and ratings system
- Wishlist functionality
- Advanced filtering options
- Admin analytics dashboard
- Multi-image support for products
- Responsive mobile design improvements
- API integration for inventory management

## 📄 License

This project is created for educational purposes and demonstration of PHP OOP concepts in e-commerce development.

## 👨‍💻 Development

Built with PHP Object-Oriented Programming principles, demonstrating:
- Class-based architecture
- Encapsulation
- Database abstraction
- Session management
- File handling
- CRUD operations

---

**BeatStrum** - Your one-stop shop for musical instruments! 🎸🥁🎹🎺

