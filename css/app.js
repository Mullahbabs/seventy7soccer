// Your Firebase configuration
 type="module">
// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.0.2/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDWu1PjZ0R5-2KJc1PYuWZK19MrWT0d58Q",
  authDomain: "seventy7sports-5f62b.firebaseapp.com",
  projectId: "seventy7sports-5f62b",
  storageBucket: "seventy7sports-5f62b.firebasestorage.app",
  messagingSenderId: "770203089833",
  appId: "1:770203089833:web:9c36ab2012a95e20f1a2b8",
  measurementId: "G-ZSPG8Z92QX"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
  const storage = firebase.storage(); // For file uploads
  
  // Reference to the database paths
  const dbRef = firebase.database().ref("players");
  
  // Function to upload file and return the file URL
  const uploadFile = async (file, fileName) => {
    const storageRef = firebase.storage().ref(`idcards/${fileName}`);
    await storageRef.put(file);
    return await storageRef.getDownloadURL();
  };
  
  // Form submission logic
  document.getElementById("dataForm").addEventListener("submit", async (event) => {
    event.preventDefault();
  
    const persons = [];
  
    // Collect data for 8 persons
    for (let i = 1; i <= 8; i++) {
      const name = document.getElementById(`name${i}`).value;
      const idCardFile = document.getElementById(`idcard${i}`).files[0];
      const email = document.getElementById(`email${i}`).value;
      const phone = document.getElementById(`phone${i}`).value;
  
      // Upload ID card file to Firebase Storage
      const idCardUrl = await uploadFile(idCardFile, `idcard_player${i}`);
  
      persons.push({ name, idCardUrl, email, phone });
    }
  
    // Collect data for 2 persons (email and phone only)
    for (let i = 9; i <= 10; i++) {
      const email = document.getElementById(`email${i}`).value;
      const phone = document.getElementById(`phone${i}`).value;
  
      persons.push({ email, phone });
    }
  
    // Save data to Firebase Realtime Database
    dbRef.set(persons)
      .then(() => {
        alert("Data saved successfully!");
        document.getElementById("dataForm").reset(); // Clear the form
      })
      .catch((error) => {
        console.error("Error saving data:", error);
      });
  });
  