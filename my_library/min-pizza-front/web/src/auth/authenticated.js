import axios from "axios";

export default (to, from, next) => {
    if (
      localStorage.getItem("customerToken") != null &&
      localStorage.getItem("customerToken").length > 0
    ) {
    
      axios
      .get("days")
      .then((response) => {
        next();
      })
      .catch((errors) => {
        if (errors.response.status === 401) {
          localStorage.removeItem("customerToken");
          next("/Login");
        }
      });
    } else {
      localStorage.removeItem("customerToken");
      next("/Login");
    }
  };