# CodyChat 11 test checklist

- [ ] PHP 8.x syntax check passes.
- [ ] Run addon installer while logged in as an administrator.
- [ ] Confirm table avatar_frame exists and boom_users.avatar_frame exists.
- [ ] Upload PNG/JPG/GIF/WebP under 5 MB in admin.
- [ ] Verify invalid file types and oversized files are rejected.
- [ ] Open player frame menu and select a frame.
- [ ] Refresh chat and verify frame persists.
- [ ] Remove frame and verify overlay disappears.
- [ ] Delete a frame in admin and verify users no longer reference it.
- [ ] Check PHP error log and browser Network/Console for errors.

Note: wallet charging is intentionally not implemented; it requires the exact wallet API from the target CodyChat build.
