You already asked for and I provided the complete `README.md` file for your GitHub repository in the previous turn\!

Here it is again, ready to be copied and pasted into a file named `README.md` in the root of your project:

-----

# 🎓 University CGPA Calculator Mobile Application

## Project Overview

This is a full-stack mobile application designed for university students to track, calculate, and visualize their academic performance. Students can register, securely log in, view their semester-wise Grade Point Average (GPA), and see their overall Cumulative Grade Point Average (CGPA). An admin role is included for managing courses, grades, and student marks.

This project was developed as a final year project submission.

## 🚀 Key Features

### Student Features

  * **Secure Authentication:** User registration and login protected by **JWT** (JSON Web Tokens).
  * **CGPA Dashboard:** Real-time calculation and display of overall **CGPA** and individual semester **GPA**.
  * **Course Details:** Detailed view of enrolled courses, credit hours, awarded marks, grades, and grade points.
  * **Cross-Platform Mobile App:** Built using **React Native (Expo)**.

### Admin Features

  * **Role-Based Access:** Protected routes ensuring only authenticated administrators can access management tools.
  * **Course Management:** API to add new courses (course code, name, credit hours, semester).
  * **Marks Entry:** API to enter student marks, which automatically triggers the backend **GPA/CGPA calculation** and grade assignment based on a predefined scale.

## 🛠️ Technology Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Frontend** | **React Native (Expo)** | Mobile application framework. |
| **Backend** | **Node.js, Express** | Fast, scalable backend REST API server. |
| **Database** | **MySQL (mysql2)** | Relational database for structured academic data. |
| **Auth** | **JWT & bcrypt** | Secure user authentication and password hashing. |
| **HTTP Client** | **Axios** | Client-side HTTP requests from the mobile app to the API. |
| **UI** | **React Native Paper** | High-quality, customizable UI components. |

## ⚙️ Setup and Installation

Follow these steps to get the application running locally on your machine.

### Prerequisites

1.  **Node.js** (v18+) & **npm**
2.  **MySQL Server** running locally (e.g., using XAMPP, MAMP, or Docker)
3.  **Expo Go App** (on your mobile device) or an **Emulator/Simulator**
4.  **IP Address:** You must know your local machine's IP address (e.g., `192.168.1.10`) for the mobile app to connect to the backend server.

-----

### Step 1: Database Setup (MySQL)

1.  **Create Database:** Log into your MySQL server and execute the contents of the provided `database_schema.sql` script (from the previous steps in our conversation). This will create the required tables and seed data, including:
      * A full grading scale (A+ to F).
      * Default semesters (1-8).
      * A default **Admin User** (`admin@university.edu`/`adminpassword`).
      * A default **Student User** (`alice@student.edu`/`studentpassword`).

-----

### Step 2: Backend Installation

1.  Navigate to the `backend/` directory:

    ```bash
    cd backend
    ```

2.  Install dependencies:

    ```bash
    npm install
    ```

3.  **Configure Environment:** Create a file named **`.env`** in the `backend/` directory and populate it with your database credentials and a secret key:

    ```env
    # DB CONFIG
    DB_HOST=localhost
    DB_USER=root
    DB_PASSWORD=your_mysql_password
    DB_DATABASE=university_gpa_calculator

    # SERVER CONFIG
    PORT=3000
    JWT_SECRET=YOUR_VERY_SECURE_JWT_SECRET_HERE
    ```

4.  **Run Server:** Start the Node.js API server:

    ```bash
    npm run dev  # Requires nodemon
    # OR
    node server.js
    ```

    The API should now be running on `http://localhost:3000`.

-----

### Step 3: Frontend Installation (Mobile App)

1.  Navigate to the `frontend/` directory:

    ```bash
    cd ../frontend
    ```

2.  Install dependencies:

    ```bash
    npm install
    npx expo install react-native-screens react-native-safe-area-context react-native-svg
    ```

3.  **Crucial Configuration:** Open **`frontend/api/client.js`** and **replace the `BASE_URL`** with your local machine's IP address:

    ```javascript
    // frontend/api/client.js

    // CHANGE THIS IP ADDRESS! Use your actual machine IP.
    const BASE_URL = 'http://YOUR_LOCAL_IP_ADDRESS:3000/api'; 
    ```

4.  **Run Mobile App:** Start the Expo development server:

    ```bash
    npx expo start
    ```

5.  Scan the displayed QR code with the **Expo Go App** on your phone, or select an emulator to launch the application.

## 🔑 Default Credentials for Testing

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@university.edu` | `adminpassword` |
| **Student**| `alice@student.edu` | `studentpassword` |

## Endpoints (API Reference)

All endpoints are prefixed with `/api`.

### Authentication

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `POST` | `/auth/register` | Register a new student user. | Public |
| `POST` | `/auth/login` | Log in and receive a JWT token. | Public |

### Student Data

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/students/:id/gpa` | Get semester GPAs and overall CGPA. | Self or Admin |
| `GET` | `/students/:id/courses` | Get all enrolled courses, marks, and grades. | Self or Admin |

### Admin Management

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `POST` | `/admin/addCourse` | Add a new course to a semester. | Admin Only |
| `POST` | `/admin/addMarks` | Enter marks for a student in a course (triggers grade calculation). | Admin Only |
| `GET` | `/admin/gradescale` | View the current university grading scale. | Admin Only |

-----

Let me know if you want to move on to the next development task, such as creating the **Admin Dashboard screens**\!
