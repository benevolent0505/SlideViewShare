'use strict';

document.getElementById('post_comment').addEventListener('input', () => {
  let postArea = document.getElementById('post_comment');

  if (postArea.value) {
    document.getElementById('post-comment-button').disabled = false;
  } else {
    document.getElementById('post-comment-button').disabled = true;
  }
});

document.getElementById('cancel-button').addEventListener('click', () => {
  document.getElementById('post_comment').value = '';
});
