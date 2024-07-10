<template>
    <div>
        <div>
            <h1>Update Image</h1><br>
            <input type="file" @change="uploadImages">
        </div>
        <br>
        <div>
            <button @click="uploadClicked()" :disabled="disabledUpload">Update Image</button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            imageId: this.$route.params.imageId,
            backendUrl: 'http://127.0.0.1:8000',
            disabledUpload: false,
            imageFile: '',
        }
    },

    methods: {
        uploadImages(event){
            // Inserting image info
            this.imageFile = event.target.files[0];
        },

        uploadClicked(){
            this.disabledUpload = true;
            
            if(this.imageFile != ''){
                const formData = new FormData();
                
                formData.append("image", this.imageFile);
                formData.append("imageId", this.imageId);

                // Calling api to update new image 
                axios.post(this.backendUrl+'/api/update-images', formData)
                .then(response => {
                    this.disabledUpload = false;
                    console.log(response);

                    // Sending back to home
                    this.$router.push(`/`);
                    
                })
                .catch(error => {
                    this.disabledUpload = false;
                    console.log(error);

                });
            }
            else{
                this.disabledUpload = false;
            }
        },
    }

}
</script>
