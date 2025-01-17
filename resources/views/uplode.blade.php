<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple PDFs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .upload-container {
            text-align: center;
            margin-top: 50px;
        }
        .upload-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .upload-button:hover {
            background-color: #45a049;
        }
        input[type="file"] {
            display: none;
        }
        .file-names {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
            text-align: left;
            max-width: 300px;
            margin: 0 auto;
        }
        .file-names li {
            list-style: none;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Upload PDF Files</h2>
        <form action="{{ route('upload.pdf.multiple') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="upload-button">
                Choose PDFs
                <input type="file" name="pdf_files[]" accept=".pdf" multiple onchange="displayFileNames(this)">
            </label>
            <ul id="file-names" class="file-names">No files selected</ul>
            <br>
            <button type="submit" class="upload-button">Upload</button>
        </form>
    </div>

    <script>
        // JavaScript function to display the names of the selected files
        function displayFileNames(input) {
            const fileList = input.files;
            const fileNamesList = document.getElementById('file-names');
            fileNamesList.innerHTML = ''; // Clear the list
            if (fileList.length > 0) {
                for (const file of fileList) {
                    const li = document.createElement('li');
                    li.textContent = file.name;
                    fileNamesList.appendChild(li);
                }
            } else {
                fileNamesList.textContent = 'No files selected';
            }
        }
    </script>
</body>
</html>
