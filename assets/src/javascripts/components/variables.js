export default {
    baseUrl: document.head.querySelector('meta[name="base-url"]').content || '',
    csrfToken: document.head.querySelector('meta[name="csrf-token"]').content || '',
    userId: document.head.querySelector('meta[name="user-id"]').content || ''
}
