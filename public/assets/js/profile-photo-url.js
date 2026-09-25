window.profilePhotoUrl = function (photo, gender) {
    if (!photo || !String(photo).trim()) {
        return '/images/profile_photos/' + (gender === 'Female' ? 'girl.jpg' : 'boy.jpg');
    }

    const path = String(photo).trim();
    if (/^(?:https?:)?\/\//i.test(path) || path.startsWith('/')) return path;
    if (path.startsWith('members/')) return '/storage/' + path;
    if (/^(?:photos|storage|images|img|uploads)\//.test(path)) return '/' + path;
    return '/photos/photo/' + path;
};
