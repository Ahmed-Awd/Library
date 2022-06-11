export default (to, from, next) => {
    if (
        localStorage.getItem("customerToken") == null ||
        localStorage.getItem("customerToken").length === 0
    ) {
        next();
    } else {
        next("/");
    }
};
