import { store } from '../framework'
import userStore from './userStore'

export default store({
    pendingUserSearchString: '',

    get pendingUsers() {
        return userStore.users.filter(user => !user.isApproved)
    },
    eventListeners: {
        
    }
})
