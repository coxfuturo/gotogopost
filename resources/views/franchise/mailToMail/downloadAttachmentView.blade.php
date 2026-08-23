<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading Files...</title>
    <script>
        function downloadFiles() {
            // Current URL se `mail_code` extract karna
            
            const pathSegments = window.location.pathname.split('/');
            const mailCode = pathSegments[pathSegments.length - 2]; // 1234567856
            const phone = pathSegments[pathSegments.length - 1]; // 9536970222
    alert(pathSegments);
            // Fetch request bhejna mailCode aur phone ke saath
            fetch(`/franchise/downloadAttachment/${mailCode}/${phone}`)
                .then(response => response.json())
                .then(data => {
                    if (data.file_urls && data.file_urls.length > 0) {
                        data.file_urls.forEach((url, index) => {
                            setTimeout(() => {
                                const link = document.createElement('a');
                                link.href = url;
                                link.download = url.split('/').pop(); // File ka naam extract karna
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            }, index * 2000); // 2 sec delay per file
                        });
                    } else {
                        alert("No files found.");
                    }
                })
                .catch(error => console.error("Error fetching files:", error));
        }
    </script>
    
</head>
<body onload="downloadFiles()">
    <h2>Your files are downloading...</h2>
</body>
</html>
