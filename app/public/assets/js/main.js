const App = {
    jsDir: "/assets/js/",
    componentsDir: this.jsDir + "components/",
    pagesDir: this.jsDir + "pages/",
    components: [],
    methods: [],
    context: [],
}

let { store, component } = reef;

App.methods.logout = () => {
    new swal({
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#d33',
    }).then(async (willLogout) => {
        if (willLogout) {
            try {
                if (!willLogout.isConfirmed)
                    return;
                // Make the POST request to log out
                await axios.post('/api/logout');
                window.location.replace("/");
            } catch (error) {
                // If there is an error, show an alert
                new swal('Error logging out', {
                    icon: 'error',
                });
            }
        }
    });
}