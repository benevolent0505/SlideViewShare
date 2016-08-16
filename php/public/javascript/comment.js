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
        let data = JSON.parse(xhr.responseText);

        const COMMENT_LIST = document.getElementById('comment-list');
        let li = document.createElement('li');
        li.classList.add('mdl-list__item');

        let cardDiv = document.createElement('div');
        cardDiv.classList.add('mdl-card');
        cardDiv.classList.add('mdl-shadow--2dp');

        let titleDiv = document.createElement('div');
        titleDiv.classList.add('mdl-card__title');
        titleDiv.classList.add('mdl-card--border');

        let paragraph = document.createElement('p');
        let created_at = moment(data.created_at);  // TODO: 書式の指定をちゃんとする
        if (isAnonymous) {
          paragraph.textContent = 'Anonymous, ' + created_at.toString();
        } else {
          paragraph.innerHTML =
            '<a href="../users/' + data.username + '">' + data.username + '</a>, ' +  created_at.toString();
        }
        titleDiv.appendChild(paragraph);

        cardDiv.appendChild(titleDiv);

        let textDiv = document.createElement('div');
        textDiv.classList.add('mdl-card__supporting-text');
        textDiv.textContent = data.content;
        cardDiv.appendChild(textDiv);

        li.appendChild(cardDiv);
        COMMENT_LIST.insertBefore(li, COMMENT_LIST.firstChild);
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
