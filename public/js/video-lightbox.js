window.openVideoLightbox = function (url, type) {
    var c = document.getElementById('videoLightboxContainer');
    if (!c) return;
    if (type === 'youtube' || type === 'vimeo') {
        c.innerHTML = '<iframe src="' + url + (type === 'youtube' ? '?autoplay=1' : '?autoplay=1') + '" allow="autoplay; fullscreen" allowfullscreen></iframe>';
    } else {
        c.innerHTML = '<video controls autoplay muted playsinline style="width:100%;height:100%"><source src="' + url + '" type="video/mp4"></video>';
    }
    document.getElementById('videoLightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
};
window.closeVideoLightbox = function (e) {
    if (e && e.target !== e.currentTarget) return;
    var c = document.getElementById('videoLightboxContainer');
    if (c) c.innerHTML = '';
    var l = document.getElementById('videoLightbox');
    if (l) l.style.display = 'none';
    document.body.style.overflow = '';
};
document.addEventListener('keydown', function (e) {
    var l = document.getElementById('videoLightbox');
    if (l && l.style.display === 'flex' && e.key === 'Escape') {
        window.closeVideoLightbox();
    }
});
