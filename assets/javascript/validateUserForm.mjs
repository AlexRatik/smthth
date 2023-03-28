export function validateUserForm(name, email, gender, status) {
    const nameRegex = /^[a-zA-Z ]{2,30}$/;
    const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

    if (!nameRegex.test(name)) {
        alert("Please enter a valid name (2-30 characters, letters only)");
        return false;
    }
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }
    if (gender !== "male" && gender !== "female") {
        alert("Please select a valid gender (Male or Female)");
        return false;
    }
    if (status !== "active" && status !== "inactive") {
        alert("Please select a valid status (Active or Inactive)");
        return false;
    }

    return true;
}