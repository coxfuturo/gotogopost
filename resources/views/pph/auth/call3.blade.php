<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebRTC Video Chat</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        video {
            width: 100%;
            max-width: 400px;
            height: auto;
            border: 3px solid #007BFF;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WebRTC Video Chat</h1>
        <div id="userList">
            <h3>Online Users</h3>
            <ul id="userListContainer"></ul>
        </div>
        <div class="video-container">
            <video id="localVideo" autoplay muted></video>
            <video id="remoteVideo" autoplay></video>
        </div>

        <div><button id="endCallButton"  class="btn btn-danger">end call</button></div>
    </div>

    <!-- Bootstrap Modal for Incoming Call -->
    <div class="modal fade" id="incomingCallModal" tabindex="-1" aria-labelledby="incomingCallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incomingCallModalLabel">Incoming Call</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="incomingCallMessage"></p>
                    <button id="acceptCallButton" class="btn btn-primary">Accept</button>
                    <button id="rejectCallButton" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.min.js"></script>
    <script>
        const userId = prompt("Enter your User ID");
        if (!userId) {
            alert("User ID is required to proceed.");
            throw new Error("User ID is required.");
        }

        const socket = io.connect("https://pxsn73gt-5000.inc1.devtunnels.ms/", {
            query: { userId }
        });
        socket.emit("addUser", userId);

        let localStream = null;
        let peerConnection = null;
        const config = {
            iceServers: [
                {
                    urls: "stun:stun.l.google.com:19302"
                }
                ]
        };


        const localVideo = document.getElementById("localVideo");
        const remoteVideo = document.getElementById("remoteVideo");
        const userListContainer = document.getElementById("userListContainer");
        const incomingCallModal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
        const incomingCallMessage = document.getElementById("incomingCallMessage");
        const acceptCallButton = document.getElementById("acceptCallButton");
        const rejectCallButton = document.getElementById("rejectCallButton");
        const endCallButton = document.getElementById("endCallButton");

        let remoteUserId = null;
        let callId = null;

        // Initialize local media
        async function initLocalMedia() {
            try {
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });
                localVideo.srcObject = localStream;
            } catch (error) {
                console.error("Error accessing media devices:", error);
                alert("Permission to access camera and microphone is required.");
            }
        }

       
        socket.on("connect", () => {
            console.log(`Connected as User ID: ${userId}`);
            initLocalMedia();
        });

        socket.on("onlineUsers", (users) => {
            userListContainer.innerHTML = "";
            users.forEach((user) => {
                if (user !== userId) {
                    const listItem = document.createElement("li");
                    listItem.textContent = `User: ${user}`;

                    const callButton = document.createElement("button");
                    callButton.textContent = "Call";
                    callButton.classList.add("btn", "btn-primary");
                    callButton.addEventListener("click", () => {
                        remoteUserId = user;
                        socket.emit("callUser", 
                        { 
                            callerId: userId,
                            receiverId: remoteUserId, 
                            callerType:"User",
                            receiverType:"User",
                            callType:"video"
                        }
                    );
                    });
                    listItem.appendChild(callButton);
                    userListContainer.appendChild(listItem);
                }
            });
        });

        // Handle incoming call
        socket.on("incomingCall", (data) => {
            console.log('incomingCall',data)
            remoteUserId = data.callerId;
            callId = data.callId
            incomingCallMessage.textContent = `Incoming call from ${data.name} ${data.phoneNumber}`;
            incomingCallModal.show();
        });

        socket.on("callId", (data) => {
            console.log('dsfasg',data)
            callId = data

        });

        // Accept the call
        acceptCallButton.addEventListener("click", () => {
            incomingCallModal.hide();
            createPeerConnection(); 
        });

    
        function createPeerConnection() {
            peerConnection = new RTCPeerConnection(config);
            localStream.getTracks().forEach((track) => peerConnection.addTrack(track, localStream));
            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    socket.emit("candidate", {
                        candidate: event.candidate,
                        to: remoteUserId
                    });
                }
            };

            peerConnection.ontrack = (event) => {
                
                remoteVideo.srcObject = event.streams[0];
            };

            // Create and send an offer after accepting the call
            peerConnection.createOffer()
                .then((offer) => peerConnection.setLocalDescription(offer))
                .then(() => {
                    socket.emit("offer", {
                        offer: peerConnection.localDescription,
                        to: remoteUserId
                    });
            }); 
        }


        // Handle incoming offer
        socket.on("offer", ({ offer, from }) => {


            console.log('Offer received:', offer);

            remoteUserId = from;
            peerConnection = new RTCPeerConnection(config);

            localStream.getTracks().forEach((track) => peerConnection.addTrack(track, localStream));

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    socket.emit("candidate", {
                        candidate: event.candidate,
                        to: from
                    });
                }
            };

            peerConnection.ontrack = (event) => {
                remoteVideo.srcObject = event.streams[0];
            };


            peerConnection.setRemoteDescription(new RTCSessionDescription(offer))
                .then(() => peerConnection.createAnswer())
                .then((answer) => peerConnection.setLocalDescription(answer))
                .then(() => socket.emit("answer", {
                    answer: peerConnection.localDescription,
                    to: from
                }));
        });

        // Handle incoming answer
        socket.on("answer", ({ answer }) => {
            console.log('Answer received:', answer);
            try {
                peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
                console.log('Remote description set successfully.');
            } catch (error) {
                console.error('Error setting remote description:', error);
            }
        });

        // Handle incoming ICE candidates
        socket.on("candidate", ({ candidate }) => {
            peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
        });

        
    </script>
</body>
</html>
