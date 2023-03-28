import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";
import {createUserCard} from "./createUserCard.mjs";

let page = 1;
let isLoading = false;
let isNoMoreRecords = false;

export function fetchUsers() {
    isLoading = true;
    handleFetchRequest(`/users?page=${page}`)
        .then(res => res.json())
        .then(data => {
            if(!data.error){
                const parent = document.getElementById('users-cards');
                data.forEach(item => createUserCard(item, parent));
                page++;
            } else {
                isNoMoreRecords = true;
                throw new Error(data.error);
            }
        })
        .catch(e => {
            alert(e.message);
        })
        .finally(() => {
            isLoading = false;
        })
}

window.addEventListener('scroll', () => {
    if (!isNoMoreRecords && !isLoading && window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
        fetchUsers();
    }
})

window.addEventListener('DOMContentLoaded', fetchUsers);