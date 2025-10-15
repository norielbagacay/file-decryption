<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Decryption</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f5f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #1d1d1f;
            font-size: 14px;
            font-weight: 500;
        }

        .file-input-wrapper {
            position: relative;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-display {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border: 2px dashed #d2d2d7;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-input-display:hover {
            border-color: #0071e3;
            background: #f0f8ff;
        }

        .file-input-display.has-file {
            border-color: #0071e3;
            border-style: solid;
            background: #f0f8ff;
        }

        .file-text {
            color: #86868b;
            font-size: 14px;
            flex: 1;
        }

        .file-input-display.has-file .file-text {
            color: #1d1d1f;
        }

        .password-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d2d2d7;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s;
            background: white;
        }

        .password-input:focus {
            outline: none;
            border-color: #0071e3;
            box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #0071e3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .submit-btn:hover:not(:disabled) {
            background: #0077ed;
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .progress-container {
            margin-top: 16px;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #f0f0f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #0071e3;
            width: 0%;
            transition: width 0.3s;
        }

        .progress-text {
            font-size: 13px;
            color: #86868b;
            margin-top: 8px;
            text-align: center;
        }

        .message {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            display: none;
        }

        .message.success {
            background: #d1f4e0;
            color: #0d5028;
        }

        .message.error {
            background: #fce8e8;
            color: #c41e3a;
        }

        .download-section {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #d2d2d7;
            display: none;
        }

        .download-card {
            background: #f5f5f7;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .file-icon-large {
            font-size: 32px;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-size: 15px;
            font-weight: 500;
            color: #1d1d1f;
            margin-bottom: 4px;
        }

        .file-size {
            font-size: 13px;
            color: #86868b;
        }

        .download-btn {
            padding: 8px 16px;
            background: #0071e3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .download-btn:hover {
            background: #0077ed;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔓 File Decryption</h1>
        
        <form id="decryptForm">
            <div class="form-group">
                <label for="encryptedFile">Encrypted File</label>
                <div class="file-input-wrapper">
                    <input type="file" id="encryptedFile" class="file-input" required>
                    <div class="file-input-display" id="fileDisplay">
                        <span class="file-text">Choose file...</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="password-input" 
                       placeholder="Enter decryption password" required>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span id="btnText">Decrypt File</span>
            </button>
            
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-text" id="progressText">Processing...</div>
            </div>
        </form>

        <div id="message" class="message"></div>

        <div class="download-section" id="downloadSection">
            <div class="download-card">
                <div class="file-icon-large">📄</div>
                <div class="file-info">
                    <div class="file-name" id="decryptedFileName">restore.sql</div>
                    <div class="file-size" id="decryptedFileSize">0 KB</div>
                </div>
                <button class="download-btn" id="downloadBtn">Download</button>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('encryptedFile');
        const fileDisplay = document.getElementById('fileDisplay');
        const fileText = fileDisplay.querySelector('.file-text');
        const form = document.getElementById('decryptForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const messageDiv = document.getElementById('message');
        const progressContainer = document.getElementById('progressContainer');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const downloadSection = document.getElementById('downloadSection');
        const downloadBtn = document.getElementById('downloadBtn');
        const decryptedFileName = document.getElementById('decryptedFileName');
        const decryptedFileSize = document.getElementById('decryptedFileSize');

        let decryptedData = null;

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileText.textContent = file.name;
                fileDisplay.classList.add('has-file');
            } else {
                fileText.textContent = 'Choose file...';
                fileDisplay.classList.remove('has-file');
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const file = fileInput.files[0];
            const password = document.getElementById('password').value;

            if (!file || !password) {
                showMessage('Please select a file and enter password', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('encryptedFile', file);
            formData.append('password', password);

            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner"></span>Decrypting...';
            progressContainer.style.display = 'block';
            progressFill.style.width = '0%';
            progressText.textContent = 'Uploading file...';
            downloadSection.style.display = 'none';
            hideMessage();

            try {
                // Simulate upload progress
                progressFill.style.width = '30%';
                
                const xhr = new XMLHttpRequest();
                
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.min((e.loaded / e.total) * 40, 40);
                        progressFill.style.width = percent + '%';
                    }
                });

                const response = await new Promise((resolve, reject) => {
                    xhr.addEventListener('load', function() {
                        if (xhr.status === 200) {
                            resolve(JSON.parse(xhr.responseText));
                        } else {
                            reject(new Error('Server error: ' + xhr.status));
                        }
                    });

                    xhr.addEventListener('error', () => reject(new Error('Connection error')));
                    
                    xhr.open('POST', 'decrypt.php', true);
                    xhr.send(formData);
                });

                progressFill.style.width = '70%';
                progressText.textContent = 'Decrypting data...';

                await new Promise(resolve => setTimeout(resolve, 300));
                
                progressFill.style.width = '100%';
                progressText.textContent = 'Complete!';

                if (response.success) {
                    showMessage('File decrypted successfully!', 'success');
                    
                    // Store decrypted file info
                    decryptedData = response;
                    decryptedFileName.textContent = response.output_file.split('\\').pop();
                    decryptedFileSize.textContent = formatFileSize(response.file_size);
                    
                    // Show download section
                    setTimeout(() => {
                        downloadSection.style.display = 'block';
                        progressContainer.style.display = 'none';
                    }, 500);
                } else {
                    throw new Error(response.error || 'Decryption failed');
                }

            } catch (error) {
                showMessage(error.message, 'error');
                progressContainer.style.display = 'none';
            } finally {
                submitBtn.disabled = false;
                btnText.textContent = 'Decrypt File';
            }
        });

        downloadBtn.addEventListener('click', function() {
            if (!decryptedData) return;
            
            // Trigger download by creating a link to the PHP download script
            const link = document.createElement('a');
            link.href = 'download.php?file=' + encodeURIComponent(decryptedData.output_file);
            link.download = decryptedFileName.textContent;
            link.click();
        });

        function showMessage(text, type) {
            messageDiv.textContent = text;
            messageDiv.className = 'message ' + type;
            messageDiv.style.display = 'block';
        }

        function hideMessage() {
            messageDiv.style.display = 'none';
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
    </script>
</body>
</html>
