let photo_snap;

function previewFile(file) {
    // プレビュー画像を追加する要素
    const preview = document.getElementById('preview');
  
    // FileReader オブジェクトを作成
    const reader = new FileReader();

    // ファイルを読み込む
    reader.readAsDataURL(file);

    // ファイルが読み込まれたときに実行する
    reader.onload = function (e) {
      const imageUrl = e.target.result; // 画像の URL は event.target.result で呼び出せる
      const img = document.createElement("img"); // img 要素を作成
      img.src = imageUrl; // 画像の URL を img要素にセット
      preview.appendChild(img); // #preview の中に追加

      let html = `
        <input type="hidden" name="up_photo" value="${imageUrl}"/>
      `;
      let image_post = $("#photo_select");
      image_post.append(html); // form に 画像送信用の hidden を追加
    }
  }
    
  // <input>でファイルが選択されたときの処理
  const fileInput = document.getElementById('upload');
  const handleFileSelect = () => {
    const files = fileInput.files;
    previewFile(files[0]);
  }
  fileInput.addEventListener('change', handleFileSelect);

