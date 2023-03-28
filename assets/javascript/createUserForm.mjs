import {validateUserForm} from "./validateUserForm.mjs";
import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";

const createUserForm = document.getElementById("create-user-form");

createUserForm.addEventListener("submit", handleCreateUser);

function handleCreateUser(e) {
    e.preventDefault();

    const name = document.getElementById("user-name").value;
    const email = document.getElementById("user-email").value;
    const gender = document.getElementById("user-gender").value;
    const status = document.getElementById("user-status").value;

    if (validateUserForm(name, email, gender, status)) {
        const user = {name, email, gender, status};

        handleFetchRequest("/users/create",
            "POST",
            JSON.stringify(user),
            {
                "Content-Type": "application/json"
            })
            .then((res) => {
                if (res.status === 201) {
                    window.location.href = "/"
                } else {
                    return res.json();
                }
            })
            .then(data => {
                if (data && data.error){
                    throw new Error(data.error);
                }
            })
            .catch(e => {
                alert(e.message)
            });
    }
}