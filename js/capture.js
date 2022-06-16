// video capture
let snap;
$("#captured_photo").hide(); // キャプチャ画像エリアは隠しておく

const video = document.getElementById("video");

navigator.mediaDevices.getUserMedia({
    video: true,
    audio: false,
  })
  .then(stream => {
    video.srcObject = stream;
    video.play();
  })
  .catch((e) => {
    console.log(e);
  }); // 動画の入力と表示

function snapshot() {
  let videoElement = document.querySelector("video");
  let canvasElement = document.querySelector("canvas");
  let context = canvasElement.getContext("2d");

  context.drawImage(videoElement, 0, 0, videoElement.width, videoElement.height);
  return canvasElement.toDataURL();
} // 最新画像をキャプチャ

$("#capture").on("click", function () {
  snap = snapshot();
  $("#canvas").show(); // キャプチャして表示
  $("#captured_photo").show(); // キャプチャ画像を表示
  
  let html = `
    <input type="hidden" name="photo" value="${snap}"/>
  `;
  let image_post = $(".image_post");
  image_post.append(html); // form に 画像送信用の hidden を追加
}); 
