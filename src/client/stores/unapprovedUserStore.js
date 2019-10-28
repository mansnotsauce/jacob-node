import { store } from '../framework'

export default store({

    unapprovedUsers: [],
    sortBy: null,
    reverseSort: false,
    unapprovedUserSearchString: '',
    selectedUnapprovedUsers: {},

    eventListeners: {
        LoginConfirmed({ entities }) {
            const { users } = entities
            this.unapprovedUsers = users.filter(u => !u.isApproved)
        },
        ReceivedUsers({ users }) {
            this.unapprovedUsers = users.filter(u => !u.isApproved)
        },
        SelectedUnapprovedUserSortBy({ sortBy }) {
            if (sortBy === this.sortBy) {
                this.reverseSort = !this.reverseSort
            }
            else {
                this.reverseSort = false
            }
            this.sortBy = sortBy
        },
        ChangedUnapprovedUserSearchString({ unapprovedUserSearchString }) {
            this.selectedUnapprovedUsers = {}
            this.unapprovedUserSearchString = unapprovedUserSearchString
        },
        ClickedSelectUnapprovedUserCheckbox({ userId }) {
            this.selectedUnapprovedUsers[userId] = !this.selectedUnapprovedUsers[userId]
        },
        ClickedSelectAllUnapprovedUsersCheckbox() {
            if (this.unapprovedUsers.every(user => this.selectedUnapprovedUsers[user.userId])) {
                this.selectedUnapprovedUsers = {}
            }
            else {
                this.unapprovedUsers.forEach(user => {
                    this.selectedUnapprovedUsers[user.userId] = true
                })
            }
        },
    }
})
