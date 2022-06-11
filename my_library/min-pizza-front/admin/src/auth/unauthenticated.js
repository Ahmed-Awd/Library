export default (to, from, next) => {
    if (
        localStorage.getItem("token") == null ||
        localStorage.getItem("token").length === 0
    ) {
        next();
    } else {
        next("/");
    }
};
