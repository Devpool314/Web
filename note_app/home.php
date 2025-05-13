<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Ghi chú tự động lưu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .note-card {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px;
      background-color: #fffefc;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .note-container {
      display: flex;
      flex-wrap: wrap;
    }
    .note-item {
      margin: 10px;
    }
    .grid-view .note-item {
      width: calc(33.333% - 20px);
    }
    .list-view .note-item {
      width: 100%;
    }
    .note-title {
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    @media (max-width: 768px) {
      .grid-view .note-item {
        width: calc(50% - 20px);
      }
    }
    @media (max-width: 576px) {
      .grid-view .note-item {
        width: 100%;
      }
    .logout-link {
            margin-top: 15px;
            display: inline-block;
        }
    }
  </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="fw-bold">Ghi Chú</h2>
      <div>
        <button id="gridBtn" class="btn btn-outline-primary btn-sm me-2 active">Dạng Lưới</button>
        <button id="listBtn" class="btn btn-outline-secondary btn-sm">Dạng Danh Sách</button>
        <button id="addNoteBtn" class="btn btn-success btn-sm ms-3">+ Thêm Ghi Chú</button>
      </div>
    </div>

    <div id="noteContainer" class="note-container grid-view"></div>
  </div>

  <a class="logout-link" href="logout.php">🚪 Đăng xuất</a>

  <script>
    const noteContainer = document.getElementById('noteContainer');
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');
    const addNoteBtn = document.getElementById('addNoteBtn');

    let notes = JSON.parse(localStorage.getItem('notes')) || [];

    function saveNotes() {
      localStorage.setItem('notes', JSON.stringify(notes));
    }

    function createNoteElement(note, index) {
      const div = document.createElement('div');
      div.className = 'note-item';

      const card = document.createElement('div');
      card.className = 'note-card';

      const titleInput = document.createElement('input');
      titleInput.className = 'form-control mb-2 fw-bold';
      titleInput.placeholder = 'Tiêu đề...';
      titleInput.value = note.title;
      titleInput.addEventListener('input', () => {
        notes[index].title = titleInput.value;
        saveNotes();
      });

      const contentInput = document.createElement('textarea');
      contentInput.className = 'form-control';
      contentInput.placeholder = 'Nội dung ghi chú...';
      contentInput.rows = 4;
      contentInput.value = note.content;
      contentInput.addEventListener('input', () => {
        notes[index].content = contentInput.value;
        saveNotes();
      });

      card.appendChild(titleInput);
      card.appendChild(contentInput);
      div.appendChild(card);
      return div;
    }

    function renderNotes() {
      noteContainer.innerHTML = '';
      notes.forEach((note, index) => {
        const noteEl = createNoteElement(note, index);
        noteContainer.appendChild(noteEl);
      });
    }

    // Thêm ghi chú mới
    addNoteBtn.addEventListener('click', () => {
      notes.unshift({ title: '', content: '' });
      saveNotes();
      renderNotes();
    });

    // Chuyển đổi dạng xem
    gridBtn.addEventListener('click', () => {
      noteContainer.classList.add('grid-view');
      noteContainer.classList.remove('list-view');
      gridBtn.classList.add('active');
      listBtn.classList.remove('active');
    });

    listBtn.addEventListener('click', () => {
      noteContainer.classList.add('list-view');
      noteContainer.classList.remove('grid-view');
      listBtn.classList.add('active');
      gridBtn.classList.remove('active');
    });

    // Lần đầu load
    renderNotes();
  </script>
</body>
</html>
