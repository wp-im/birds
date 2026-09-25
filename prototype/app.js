(function () {
  'use strict';

  const postList = document.querySelector('#post-list');
  const emptyState = document.querySelector('#empty-state');
  const searchInput = document.querySelector('#search-input');
  const feedLabel = document.querySelector('#feed-label');
  const feedDescription = document.querySelector('#feed-description');
  const postCount = document.querySelector('#post-count');
  const footerCount = document.querySelector('#footer-count');
  const banner = document.querySelector('#new-posts-banner');
  const pendingCount = document.querySelector('#pending-count');
  const toast = document.querySelector('#toast');
  const composeText = document.querySelector('#compose-text');
  const composeStatus = document.querySelector('#compose-status');
  const paletteSelect = document.querySelector('#palette-select');
  let activeTab = 'live';
  let activeTopic = 'all';
  let totalPosts = 1284;
  let pendingPosts = [];
  let toastTimer;

  paletteSelect.addEventListener('change', function () {
    document.body.dataset.palette = paletteSelect.value;
  });

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function showToast(message) {
    toast.textContent = message;
    toast.hidden = false;
    window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(function () {
      toast.hidden = true;
    }, 2800);
  }

  function updateClock() {
    const now = new Date();
    document.querySelector('#clock').textContent = now.toLocaleTimeString('zh-CN', { hour: '2-digit', minute: '2-digit' });
  }

  function postTemplate(data) {
    const paragraphs = data.text.split(/\n+/).filter(Boolean).map(function (paragraph) {
      return '<p>' + escapeHtml(paragraph) + '</p>';
    }).join('');
    const topic = escapeHtml(data.topic || 'Notes');
    const latest = data.latest ? '<div class="latest-reply"><strong>' + escapeHtml(data.latest.author) + '</strong><span>' + escapeHtml(data.latest.text) + '</span></div>' : '';
    const replyCount = data.replies ? '<span class="reply-count">' + data.replies + '</span>' : '';
    return '<article id="' + escapeHtml(data.id) + '" class="post is-new" data-post-id="' + escapeHtml(data.id) + '" data-topic="' + topic + '" data-following="true" data-search="' + escapeHtml(data.text + ' ' + topic) + '">' +
      '<a class="avatar" href="#profile" aria-label="打开 ' + escapeHtml(data.name) + ' 的资料">' + escapeHtml(data.initials) + '</a>' +
      '<div class="post-main"><div class="meta"><a href="#profile"><strong>' + escapeHtml(data.name) + '</strong></a> <span class="handle">' + escapeHtml(data.handle) + '</span> · <a href="#' + escapeHtml(data.id) + '">' + escapeHtml(data.time) + '</a></div>' +
      '<div class="bodytext">' + paragraphs + '</div>' +
      '<div class="post-tools"><button class="tool-button" type="button" data-action="reply">Reply ' + replyCount + '</button><button class="tool-button" type="button" data-action="like" aria-pressed="false"><span class="like-symbol">♡</span> <span class="like-count">0</span></button><button class="tool-button" type="button" data-action="bookmark" aria-pressed="false">Save</button><a class="tool-link" href="#' + escapeHtml(data.id) + '">Permalink</a><a class="topic-tag" href="#topics">' + topic + '</a></div>' +
      latest +
      '<div class="reply-box" hidden><label class="sr-only" for="reply-' + escapeHtml(data.id) + '">回复 ' + escapeHtml(data.name) + '</label><textarea id="reply-' + escapeHtml(data.id) + '" rows="2" placeholder="Reply to this thread…"></textarea><button class="push small" type="button" data-action="submit-reply">Reply</button></div></div></article>';
  }

  function updateBanner() {
    pendingCount.textContent = String(pendingPosts.length);
    banner.hidden = pendingPosts.length === 0;
  }

  function insertPendingPosts() {
    pendingPosts.reverse().forEach(function (data) {
      postList.insertAdjacentHTML('afterbegin', postTemplate(data));
      totalPosts += 1;
    });
    pendingPosts = [];
    updateBanner();
    updateCounts();
    applyFilters();
    showToast('新消息已进入 Live Feed');
  }

  function updateCounts() {
    postCount.textContent = totalPosts.toLocaleString('en-US') + ' posts';
    footerCount.textContent = totalPosts.toLocaleString('en-US');
  }

  function applyFilters() {
    const query = searchInput.value.trim().toLowerCase();
    const posts = Array.from(postList.querySelectorAll('.post'));
    let visible = 0;
    posts.forEach(function (post) {
      const matchesTab = activeTab === 'live' || post.dataset.following === 'true';
      const matchesTopic = activeTopic === 'all' || post.dataset.topic === activeTopic;
      const matchesQuery = !query || post.dataset.search.toLowerCase().includes(query);
      const show = matchesTab && matchesTopic && matchesQuery;
      post.hidden = !show;
      if (show) visible += 1;
    });
    emptyState.hidden = visible !== 0;
    feedLabel.textContent = activeTab === 'following' ? 'Following' : (activeTopic === 'all' ? 'Live' : activeTopic);
    feedDescription.textContent = activeTab === 'following' ? 'people you follow' : (query ? 'matching this search' : 'ordered by recent activity');
  }

  function setTab(tab) {
    activeTab = tab;
    document.querySelectorAll('[data-tab]').forEach(function (control) {
      control.classList.toggle('is-active', control.dataset.tab === tab);
    });
    if (tab === 'topics') {
      document.querySelector('#topics').scrollIntoView({ behavior: 'smooth', block: 'start' });
      return;
    }
    if (tab === 'search') {
      searchInput.focus();
      return;
    }
    applyFilters();
  }

  function submitNewPost() {
    const text = composeText.value.trim();
    if (!text) {
      composeStatus.textContent = '先写一点内容，再发布';
      composeText.focus();
      return;
    }
    const id = 'p-' + Date.now();
    postList.insertAdjacentHTML('afterbegin', postTemplate({
      id: id,
      initials: 'ED',
      name: 'Editor',
      handle: '@editor',
      time: '刚刚',
      topic: 'Notes',
      text: text,
      replies: 0,
      latest: null
    }));
    composeText.value = '';
    totalPosts += 1;
    updateCounts();
    applyFilters();
    composeStatus.textContent = '已发布 · live feed 已更新';
    showToast('你的消息已经发布');
    document.querySelector('#' + id).scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function handlePostAction(button) {
    const post = button.closest('.post');
    const action = button.dataset.action;
    if (!post) return;
    if (action === 'like') {
      const pressed = button.getAttribute('aria-pressed') === 'true';
      const count = button.querySelector('.like-count');
      count.textContent = String(Number(count.textContent) + (pressed ? -1 : 1));
      button.setAttribute('aria-pressed', String(!pressed));
      return;
    }
    if (action === 'bookmark') {
      const pressed = button.getAttribute('aria-pressed') === 'true';
      button.setAttribute('aria-pressed', String(!pressed));
      button.textContent = pressed ? 'Save' : 'Saved';
      showToast(pressed ? '已取消收藏' : '已收藏到你的阅读清单');
      return;
    }
    if (action === 'reply') {
      const replyBox = post.querySelector('.reply-box');
      replyBox.hidden = !replyBox.hidden;
      button.setAttribute('aria-expanded', String(!replyBox.hidden));
      if (!replyBox.hidden) replyBox.querySelector('textarea').focus();
      return;
    }
    if (action === 'submit-reply') {
      const replyBox = button.closest('.reply-box');
      const textarea = replyBox.querySelector('textarea');
      const value = textarea.value.trim();
      if (!value) {
        textarea.focus();
        return;
      }
      let latest = post.querySelector('.latest-reply');
      if (!latest) {
        latest = document.createElement('div');
        latest.className = 'latest-reply';
        post.querySelector('.post-main').insertBefore(latest, replyBox);
      }
      latest.innerHTML = '<strong>Editor</strong><span>' + escapeHtml(value) + '</span>';
      const replyButton = post.querySelector('[data-action="reply"]');
      const count = replyButton.querySelector('.reply-count');
      if (count) count.textContent = String(Number(count.textContent) + 1);
      textarea.value = '';
      replyBox.hidden = true;
      replyButton.setAttribute('aria-expanded', 'false');
      showToast('回复已加入这个 thread');
    }
  }

  document.querySelector('#compose-form').addEventListener('submit', function (event) {
    event.preventDefault();
    submitNewPost();
  });

  composeText.addEventListener('keydown', function (event) {
    if ((event.metaKey || event.ctrlKey) && event.key === 'Enter') {
      event.preventDefault();
      submitNewPost();
    }
  });

  document.querySelectorAll('[data-compose-mode]').forEach(function (button) {
    button.addEventListener('click', function () {
      document.querySelectorAll('[data-compose-mode]').forEach(function (control) { control.classList.remove('is-active'); });
      button.classList.add('is-active');
      composeStatus.textContent = button.dataset.composeMode === 'post' ? '⌘↵ 发布 · 标题可选' : button.dataset.composeMode === 'link' ? '粘贴链接 · 稍后生成预览' : 'Note · 轻量记录';
    });
  });

  postList.addEventListener('click', function (event) {
    const button = event.target.closest('[data-action]');
    if (button) handlePostAction(button);
  });

  document.querySelector('#search-form').addEventListener('submit', function (event) {
    event.preventDefault();
    applyFilters();
    showToast(searchInput.value.trim() ? '已筛选当前信息流' : '已恢复 Live Feed');
  });
  searchInput.addEventListener('input', applyFilters);

  document.querySelectorAll('[data-tab]').forEach(function (control) {
    control.addEventListener('click', function () {
      setTab(control.dataset.tab);
      const menu = control.closest('details');
      if (menu) menu.removeAttribute('open');
    });
  });

  document.querySelectorAll('.rail-list [data-topic]').forEach(function (control) {
    control.addEventListener('click', function () {
      activeTopic = control.dataset.topic;
      document.querySelectorAll('.rail-list [data-topic]').forEach(function (item) { item.classList.toggle('is-active', item.dataset.topic === activeTopic); });
      applyFilters();
      showToast(activeTopic === 'all' ? '显示全部 activity' : '已切换到 ' + activeTopic);
    });
  });

  document.querySelector('#show-new').addEventListener('click', insertPendingPosts);
  document.querySelector('#demo-update').addEventListener('click', function () {
    pendingPosts.push({
      id: 'live-' + Date.now(),
      initials: 'AY',
      name: 'Aya',
      handle: '@aya',
      time: '刚刚',
      topic: 'Editorial',
      text: '刚刚有人在首页发布了一条新消息。信息流应该让阅读、回应和继续工作发生在同一张桌面上。',
      replies: 1,
      latest: { author: 'Mori', text: '这就是 live surface 的价值。' }
    });
    updateBanner();
    showToast('收到一条新消息 · 点击顶部按钮显示');
  });

  document.querySelector('#attach-button').addEventListener('click', function () {
    showToast('原型阶段：这里将接入 WordPress Media Library');
  });

  document.querySelector('#newer-button').addEventListener('click', function () { showToast('已经在最新一页'); });
  document.querySelector('#older-button').addEventListener('click', function () { showToast('下一步接入 WordPress keyset pagination'); });

  updateClock();
  window.setInterval(updateClock, 1000);
  window.setTimeout(function () {
    if (!pendingPosts.length) {
      pendingPosts.push({
        id: 'live-auto',
        initials: 'LI',
        name: 'Lin',
        handle: '@lin',
        time: '刚刚',
        topic: 'Tokyo',
        text: '新的现场记录已经抵达。先读，再回一句；首页会保持安静，但不会停止更新。',
        replies: 0,
        latest: null
      });
      updateBanner();
    }
  }, 9000);
}());
