<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebRTC Group call</title>
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
            max-width: 250px;
            height: auto;
            border: 3px solid #007BFF;
            border-radius: 8px;
            margin: 10px 0;
        }

        #videoContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        #videoContainer video {
            width: 250px;
            border-radius: 10px;
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

        <div id="videoContainer">
            <video id="localVideo" autoplay muted></video>
        </div>

        <div><button id="endCallButton" class="btn btn-danger">end call</button></div>
    </div>

    <!-- Bootstrap Modal for Incoming Call -->
    <div class="modal fade" id="incomingCallModal" tabindex="-1" aria-labelledby="incomingCallModalLabel"
        aria-hidden="true">
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
        // Check if userId is present in the query string
        const urlParams = new URLSearchParams(window.location.search);
        const userIdFromQuery = urlParams.get('userId');

        // If userId is not in the query, show the prompt
        const userId = userIdFromQuery ? userIdFromQuery : prompt("Enter your User ID (e.g., 66e28f585eb2019161d86212 === 670f6eb796478cc0eef8a267 === 66e2931e06fe23571ac2fcd6)");

        // If no userId is provided, show alert and throw error
        if (!userId) {
            alert("User ID is required to proceed.");
            throw new Error("User ID is required.");
        }

        const socket = io.connect("https://pxsn73gt-5000.inc1.devtunnels.ms/", {
            query: { userId }
        });
        socket.emit("addUser", userId);

        let localStream = null;
        // let peerConnection = null;
        const config = {
            iceServers: [
                {
                    urls: "stun:stun.l.google.com:19302"
                }
            ]
        };


        const localVideo = document.getElementById("localVideo");
        const userListContainer = document.getElementById("userListContainer");
        const incomingCallModal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
        const incomingCallMessage = document.getElementById("incomingCallMessage");
        const acceptCallButton = document.getElementById("acceptCallButton");
        const rejectCallButton = document.getElementById("rejectCallButton");
        const endCallButton = document.getElementById("endCallButton");
        const videoContainer = document.getElementById("videoContainer");
        let peerConnections = {};
        let remoteUserId = null;
        let callId = null;
        let communityId = "681871002230cca810928250";
        // let communityId = "67768e4b0e96be71ee753d86";

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
            initLocalMedia();
        });

        socket.on("online-users", (users) => {
            userListContainer.innerHTML = "";
            const listItem = document.createElement("li");
            listItem.textContent = `LogInUser: ${userId}`;
            userListContainer.appendChild(listItem);

            // Clear existing group call button if it exists
            const existingButton = document.getElementById("groupCallButton");
            if (existingButton) {
                existingButton.remove();
            }

            // Create a single button for initiating a group call
            //if (users.length > 1) {
                const callButton = document.createElement("button");
                callButton.textContent = "Start Group Call";
                callButton.classList.add("btn", "btn-primary");
                callButton.id = "groupCallButton";

                // Attach click event to start a group call
                callButton.addEventListener("click", () => {
                    socket.emit("groupCall", {
                        callerId: userId,
                        communityId: communityId,
                        callType: "video"
                    });
                });

                // Append the button below the user list
                userListContainer.appendChild(callButton);
           // }
        });

        // Handle incoming call
        socket.on("incomingGroupCall", (data) => {
            console.log('incommingGroupCall', data)
            remoteUserId = data.callerId;
            callId = data.callId
            incomingCallMessage.textContent = `Incoming call from ${data.name} ${data.phoneNumber} in ${data.communityName}`;
            incomingCallModal.show();
        });


        // Accept the call
        acceptCallButton.addEventListener("click", () => {
            incomingCallModal.hide();
            console.log('acceptCallButton', callId)
            socket.emit("joinGroupCallRoom", { callId: callId });
        });


        //leave call
        endCallButton.addEventListener("click", () => {
            console.log('leaveGroupCall', callId);
            // Notify server to leave the group call
            socket.emit("leaveGroupCall", { callId });

            // Close all peer connections
            Object.keys(peerConnections).forEach((userId) => {
                peerConnections[userId].close();
                delete peerConnections[userId];

                // Remove the corresponding video element
                const video = document.getElementById(userId);
                if (video) video.remove();
            });

            // Reset local stream
            if (localStream) {
                localStream.getTracks().forEach((track) => track.stop());
                localVideo.srcObject = null;
            }

            console.log("You have left the call.");
        });


        // reject call
        rejectCallButton.addEventListener("click", () => {
            socket.emit("rejectGroupCall", { callId: callId });
        });

        socket.on("userLeft", ({ userDetails }) => {
            const userId = userDetails._id
            console.log('user id ', userId)
            if (peerConnections[userId]) {
                peerConnections[userId].close();
                delete peerConnections[userId];
                // Remove the corresponding video element
                const video = document.getElementById(userId);
                if (video) video.remove();
            }

            console.log('user left')
        })

        socket.on("newUserJoined", ({ userDetails }) => {
            console.log("User connected:", userDetails);

            callId = userDetails.callId
            console.log('callId', callId)
            connectToUser(userDetails._id);
        });

        socket.on("groupOffer", async ({ from, offer }) => {
            if (!peerConnections[from]) createPeerConnection(from);
            const pc = peerConnections[from];
            await pc.setRemoteDescription(new RTCSessionDescription(offer));
            const answer = await pc.createAnswer();
            await pc.setLocalDescription(answer);
            socket.emit("groupAnswer", { to: from, answer });
        });

        socket.on("groupAnswer", async ({ from, answer }) => {

            const pc = peerConnections[from];
            if (pc) await pc.setRemoteDescription(new RTCSessionDescription(answer));
        });

        socket.on("groupCandidate", ({ from, candidate }) => {
            const pc = peerConnections[from];
            if (pc) pc.addIceCandidate(new RTCIceCandidate(candidate));
        });

        function connectToUser(userId) {
            if (peerConnections[userId]) {
                console.log(`Peer connection with ${userId} already exists.`);
                return;
            }

            console.log(`Creating new peer connection for ${userId}`);
            const pc = createPeerConnection(userId);
            pc.createOffer()
                .then((offer) => pc.setLocalDescription(offer))
                .then(() => {
                    socket.emit("groupOffer", { to: userId, offer: pc.localDescription });
                });
        }



        function createPeerConnection(userId) {
            const pc = new RTCPeerConnection({
                iceServers: [{ urls: "stun:stun.l.google.com:19302" }],
            });

            pc.onicecandidate = (event) => {
                if (event.candidate) socket.emit("groupCandidate", { to: userId, candidate: event.candidate });
            };

            pc.ontrack = (event) => {
                let video = document.getElementById(userId);
                if (!video) {
                    video = document.createElement("video");
                    video.id = userId;
                    video.srcObject = event.streams[0];
                    video.autoplay = true;
                    video.playsInline = true;
                    videoContainer.appendChild(video);
                }
            };

            localStream.getTracks().forEach((track) => pc.addTrack(track, localStream));
            peerConnections[userId] = pc;
            return pc;
        }




    </script>
</body>

</html>