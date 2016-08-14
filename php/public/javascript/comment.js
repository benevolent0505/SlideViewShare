'use strict';

document.getElementById('post_comment').addEventListener('input', () => {
  let postArea = document.getElementById('post_comment');

  if (postArea.value) {
    document.getElementById('post-comment-button').disabled = false;
  } else {
    document.getElementById('post-comment-button').disabled = true;
  }
});

document.getElementById('post-comment-button').addEventListener('click', () => {
  let userId = document.getElementById('user_id').value;
  let slideId = document.getElementById('slide_id').value;
  let isAnonymous = document.getElementById('anonymous-check').checked;
  let commentContent = document.getElementById('post_comment').value;

  userId = isAnonymous ? '0' : userId;

  let xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200 || xhr.status == 304) {
        let data = xhr.responseText; // responseXML もあり
        console.log( 'COMPLETE! :' + data);
      }
    }
  };

  xhr.open('POST', "../comments/create", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send("user_id="+userId+"&slide_id="+slideId+"&content="+encodeURIComponent(commentContent));

  document.getElementById('post_comment').value = '';
  document.getElementById('post-comment-button').disabled = true;
});

document.getElementById('cancel-button').addEventListener('click', () => {
  document.getElementById('post_comment').value = '';
  document.getElementById('post-comment-button').disabled = true;
});
