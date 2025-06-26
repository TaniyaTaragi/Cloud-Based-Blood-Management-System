# Cloud-Based Blood Management System

A centralized, cloud-ready web application that connects **blood donors** and **recipients** through an efficient, user-friendly interface. Built using **HTML, CSS, JavaScript, PHP**, and **MySQL**, and deployed on **AWS EC2**, this system streamlines the process of blood donation and management for individuals and hospitals.

---
📚 Academic Context
This project was developed as a group project for the subject Cloud Computing and Virtualization during our B.Tech program at Graphic Era Hill University, Haldwani Campus.
The objective was to create a cloud-integrated web application demonstrating the use of cloud services such as AWS EC2 for hosting, along with concepts like scalability, availability, and resource provisioning in a real-world system.

👨‍💻 Contributors
- [**Taniya Taragi**](https://github.com/TaniyaTaragi)
- [**Sumit Deolia**](https://github.com/sumitdeolia27)
- [**Lavi Joshi**](https://github.com/Lavijoshi18)
- [**Shubham Singh Rawat**](https://github.com/ShubhamSinghRawat10)

## Features
- User authentication and role-based access
- Separate panels for donors, recipients, and admins
- Donor & receiver registration forms
- Request & manage blood units
- Admin dashboard to track availability and requests
- Cloud deployment on AWS EC2
- 🗃Cloud database integration using MySQL
- Responsive UI using Bootstrap
- ile support for medical documents (optional via AWS S3)

---

## 🧰 Tech Stack

| Component   | Technology           |
|-------------|----------------------|
| Frontend    | HTML, CSS, JavaScript, Bootstrap |
| Backend     | PHP                  |
| Database    | MySQL                |
| Cloud Host  | AWS EC2              |
| Cloud DB    | (Optional) Amazon RDS |
| Storage     | (Optional) AWS S3     |

---


## 🛠️ Setup Instructions

### 🔧 Prerequisites
- AWS account
- EC2 instance with Ubuntu or Amazon Linux
- LAMP stack installed (Apache, MySQL, PHP)

### 1. Clone the Repository
'''bash
git clone https://github.com/TaniyaTaragi/Cloud-Based-Blood-Management-System.git
### 2. Upload Files to EC2
Use scp or any SFTP client (like FileZilla) to upload your files to /var/www/html/.
### 3. Import Database
-SSH into your EC2 instance
-Login to MySQL
-Create and import the database
-Update your PHP DB connection file:
### 4. Run the Application
Visit: http://<your-ec2-public-ip>

📸 Screenshots
![Home Page](homepage.jpg)

✨Future Enhancements
-SMS/Email alerts using AWS Lambda or Twilio
-Geo-location based donor matching
-Real-time blood stock updates using Firebase or sockets
-AI-based suggestion for donation intervals
-Audit logging for admin activities

📜License
-This project is open-source and available under the MIT License.

🤝Contributing
-Pull requests are welcome! If you'd like to suggest improvements or report issues, feel free to open an issue.

📬 Contact
-Taniya Taragi
📧 taniyataragi10@gmail.com
🌐 LinkedIn
https://www.linkedin.com/in/taniya-taragi-479369326/
