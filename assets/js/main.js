window.addEventListener( 'load', () => {
    const pathname = window.location.pathname

    if ( pathname == '/teachers' || pathname == '/teachers/' ) {
        teachersConfig.init()
    } 

    if ( pathname == '/schools' || pathname == '/schools/' ) {
        schoolsConfig.init()
    } 
} )

const teachersConfig = {
    init: function() {
        console.log( '~main.js teachersConfig~' )

        this.formEvents()
    },
    
    formEvents: function() {
        const teacherForm = document.getElementById( 'teacherForm' )
        teacherForm.addEventListener( 'submit', ( e ) => {
            e.preventDefault()
            const formData = new FormData( teacherForm )
            const teacherFormSubmit = document.getElementById( 'teacherFormSubmit' )
            const searchParams = new URLSearchParams( window.location.search )
            const apiURL = searchParams.get( 'teacher_id' ) ? '/api/teachers/update' : '/api/teachers/new'

            teacherFormSubmit.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing
            `

            fetch( apiURL, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            } ).then( r => r.json() ).then( res => {
                teacherFormSubmit.innerHTML = `Submit`
                Swal.fire({
                    icon: res.success ? 'success' : 'info',
                    title: res.title,
                    text: res.text
                }).then(() => {
                    if ( res.success ) {
                        window.location.reload()
                    }
                })
            } )
        } )
    }
}

const schoolsConfig = {
    init: function() {
        console.log( '~main.js schoolsConfig~' )

        this.isActive()
        this.formEvents()
    },

    isActive: function() {
        const statusSelectors = [
            'school_status', 'u_school_status'
        ]

        statusSelectors.forEach( ( el ) => {
            const schoolStatus = document.getElementById( el )
            const schoolStatusText = document.getElementById( el + '_text' )

            if ( ! schoolStatus ) return 

            schoolStatus.addEventListener( 'change', ( e ) => {
                let status = 'Inactive'
                schoolStatus.classList.remove( 'bg-primary' )
    
                if ( schoolStatus.checked ) {
                    status = 'Active'
                    schoolStatus.classList.add( 'bg-primary' )
                }
    
                schoolStatusText.innerHTML = `Status: ${status}`
            } )

        } )

        const schoolDelete = document.getElementById ( 'schoolDelete' )
        if ( schoolDelete ) {
            schoolDelete.addEventListener( 'click', () => {
                const id = schoolDelete.getAttribute( 'data-id' )

                Swal.fire({
                    icon: 'info',
                    title: 'Are you sure?',
                    text: 'School will be deleted!',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Confirm',
                }).then( ( { isConfirmed } ) => {
                    if ( isConfirmed ) {
                        const formData = new FormData()
                        formData.append( 'id', id )

                        fetch( '/api/schools/delete', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        } ).then( r => r.json() ).then( res => {
                            Swal.fire({
                                icon: res.success ? 'success' : 'info',
                                title: res.title,
                                text: res.text
                            }).then( () => {
                                if ( res.success ) {
                                    window.location.reload()
                                }
                            } )
                        } )
                    }
                } )
            } )
        }

        const schoolRestore = document.getElementById ( 'schoolRestore' )
        if ( schoolRestore ) {
            schoolRestore.addEventListener( 'click', () => {
                const id = schoolRestore.getAttribute( 'data-id' )

                Swal.fire({
                    icon: 'info',
                    title: 'Are you sure?',
                    text: 'School will be restored!',
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Confirm',
                }).then( ( { isConfirmed } ) => {
                    if ( isConfirmed ) {
                        const formData = new FormData()
                        formData.append( 'id', id )

                        fetch( '/api/schools/restore', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        } ).then( r => r.json() ).then( res => {
                            Swal.fire({
                                icon: res.success ? 'success' : 'info',
                                title: res.title,
                                text: res.text
                            }).then( () => {
                                if ( res.success ) {
                                    window.location.reload()
                                }
                            } )
                        } )
                    }
                } )
            } )
        }
    },
    
    formEvents: function() {
        const schoolForm = document.getElementById( 'schoolForm' )

        schoolForm.addEventListener( 'submit', ( e ) => {
            e.preventDefault()
            const formData = new FormData( schoolForm )
            const schoolFormSubmit = document.getElementById( 'schoolFormSubmit' )
            const searchParams = new URLSearchParams( window.location.search )
            const apiURL = searchParams.get( 'school_id' ) ? '/api/schools/update' : '/api/schools/new'

            schoolFormSubmit.classList.add( 'disabled' )
            schoolFormSubmit.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing
            `;

            fetch( apiURL, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            } ).then( r => r.json() ).then( res => {
                schoolFormSubmit.innerHTML = `Submit`
                schoolFormSubmit.classList.remove( 'disabled' )
                Swal.fire({
                    icon: res.success ? 'success' : 'info',
                    title: res.title,
                    text: res.text
                }).then(() => {
                    if ( res.success ) {
                        window.location.reload()
                    }
                })
            } )
        } )
    }
}
