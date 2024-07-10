<template>
    <div>
        <div>
            <label>Select Images / Drag and drop images</label><br>
            <input type="file" accept="image/*" multiple="multiple" @change="uploadMultiImages" id="my-file">
        </div>
        <br>
        <div>
            <button @click="uploadClicked()" :disabled="disabledUpload">Upload Images</button>
        </div>

        <div style="height: 50px;"></div>
        <div v-if="showListOfImages == true">
            <table>
                <tr>
                    <th>Serial no.</th>
                    <th>Image ID</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                
                <tr v-for="(ftedImages, index) in fetchedImages" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{ftedImages.id}}</td>
                    <td><img :src="ftedImages.file_path" style="height: 200px; width: 200px; border: 1px solid black;"></td>
                    <td><router-link :to="'/update-image/'+ftedImages.id">Update</router-link> | <button @click="deleteImage(ftedImages.id, ftedImages.file_path)">Delete</button></td>
                </tr>
                
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            disabledUpload: false,
            imageList: [],
            backendUrl: 'http://127.0.0.1:8000',
            showListOfImages: false,
            fetchedImages: []
        }
    },

    created(){
        // Calling this function so that when page is loaded images also get loaded
        this.getAllTheImages();
    },

    methods: {
        getAllTheImages(){
            this.fetchedImages = [];
            // Using api fetching images 
            axios.get(this.backendUrl+'/api/get-images')
            .then(response => {
                console.log(response);
                // Checking if api returned images, if length is zero then List will be hidden, if not then it will shown
                if(response.data.data.length == 0){
                    this.showListOfImages = false;
                }
                else{
                    this.showListOfImages = true;
                    // Storing all images so that images can be shown
                    response.data.data.forEach(element => {
                        this.fetchedImages.push(element);
                    });
                }
            })
            .catch(error => {
                console.log(error);

            });
        },

        uploadMultiImages(event) {
            var input = event.target;
            var count = input.files.length;
            var index = 0;
            // Checking if images are selected
            if(input.files) {
                // Running loop on selected images
                while(count --) {
                    // Inserting images so that images can be uploaded
                    this.imageList.push(input.files[index]);
                    index ++;
                }
            }
        },

        uploadClicked(){
            this.disabledUpload = true;
            const formData = new FormData();
            // Inserting images in formdata
            for(let i=0; i<this.imageList.length; i++){
                formData.append("images[]", this.imageList[i]);
            }

            // Calling api and sending images
            axios.post(this.backendUrl+'/api/upload-images', formData)
            .then(response => {
                this.disabledUpload = false;
                // Calling this function so that newly inserted images can be loaded
                this.getAllTheImages();
                // Clearing recently selected images
                this.imageList = [];
                console.log(response);
                
            })
            .catch(error => {
                this.disabledUpload = false;
                console.log(error);

            });
        },

        deleteImage(id, file_path){
            const formData = new FormData();
            
            formData.append("imageId", id);
            formData.append("imagePath", file_path);

            // Calling api and sending image to delete image
            axios.post(this.backendUrl+'/api/delete-images', formData)
            .then(response => {
                // Calling this function so that update list of images can be shown
                this.getAllTheImages();
                console.log(response);
                
            })
            .catch(error => {
                console.log(error);

            });
        }
    }
}
</script>