importScripts('https://www.gstatic.com/firebasejs/4.0.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.0.0/firebase-messaging.js');
var config = {
    apiKey: "AIzaSyC3hFIERXkG-VeJq0JNg5cQE_M2pqVVRaw",
    projectId: "schedullo-test1",
    messagingSenderId: "168322553740"
  };
  
  
  // Initialize the default app
var defaultApp = firebase.initializeApp(config);


const messaging = firebase.messaging();

