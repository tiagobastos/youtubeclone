<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload</div>

                    <div class="panel-body">
                        <input type="file" name="video" id="video" accept="video/*" @change="fileInputChange" v-if="!uploading">

                        <div class="alert alert-danger" v-if="failed">Something went wrong. Please try again.</div>

                        <div id="video-form" v-if="uploading && !failed">
                            <div class="alert alert-info" v-if="!uploadingComplete">
                                Your video will be available at <a v-bind:href="$root.url + '/videos/' + uid">{{$root.url}}/videos/{{uid}}</a>
                            </div>
                            <div class="alert alert-success" v-if="uploadingComplete">
                                Upload complete. Video is now processing. <a href="/videos">Go to your videos</a>.
                            </div>

                            <div class="progress" v-if="!uploadingComplete">
                                <div class="progress-bar" v-bind:style="{width: fileProgress +'%' }"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="title">Title</label>    
                                <input type="text" name="title" id="title" class="form-control" v-model="title">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>    
                                <textarea name="description" id="description" class="form-control" v-model="description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="visibility">Visibility</label>
                                <select class="form-control" name="visibility" id="visibility" v-model="visibility">
                                    <option value="private">Private</option>
                                    <option value="unlisted">Unlisted</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>

                            <span class="help-block pull-right">{{ saveStatus }}</span>
                            <button type="submit" class="btn btn-default" @click.prevent="update">Save Changes</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                uploading: false,
                uploadingComplete: false,
                failed: false,
                saveStatus: null,
                fileProgress: 0,

                uid: null,
                title: 'Untitled',
                description: null,
                visibility: 'private'
            }
        },
        methods: {
            fileInputChange() {
                this.uploading = true;
                this.failed = false;

                this.file = document.getElementById('video').files[0];

                this.store().then((response) => {
                    var form = new FormData();

                    form.append('video', this.file);
                    form.append('uid', this.uid);

                    this.$http.post('/upload', form, {
                        progress: (e) => {
                            if (e.lengthComputable) {
                                this.updateProgress(e);
                            }
                        }
                    }).then(() => {
                        this.uploadingComplete = true;
                    }, () => {
                        this.failed = true;
                    });
                }, () => {
                    this.failed = true;
                })
            },
            store() {
                return this.$http.post('/videos', {
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility,
                    extension: this.file.name.split('.').pop()
                }).then((response) =>  {                    
                    this.uid = response.body.data.uid;
                    // this.uid = response.json().data.uid;
                });
            },
            update() {
                this.saveStatus = 'Saving changes';

                return this.$http.put('/videos/' + this.uid, {
                    title: this.title,
                    description: this.description,
                    visibility: this.visibility                    
                }).then((response) => {
                    this.saveStatus = 'Changes saved';

                    setTimeout(() => {
                        this.saveStatus = null;
                    }, 3000);
                }, () => {
                    this.saveStatus = 'Failed to save changes';
                });
            },
            updateProgress(e) {
                e.percent = (e.loaded / e.total) * 100;
                this.fileProgress = e.percent;
            }
        },
        // ready() {
        mounted() {
            window.onbeforeunload = () => {
                if (this.uploading && !this.uploadingComplete && !this.failed) {
                    return "Are you sure you want to navigate away?";
                }
            }
        }
    }
</script>
