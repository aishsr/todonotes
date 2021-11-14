<template>
   <div>
     <h1> To Do Notes </h1>

     <hr>

     <p> Name: </p>
     <input
      type='text'
      v-model='name'
      />

      <hr>

     <p> Username: </p>
     <input
      type='text'
      v-model='username'
      />

     <p> Password: </p>
     <input
      type='password'
      v-model='password'
      />
    <br><br>

    <button @click='login'> Login </button>
    <br><br>
    <div v-for="i in login_status" :key="i">
      <div class="item">
        {{ i.status }}
      </div>
    </div>

    <hr>

    No account?
    <button @click='signup'> Signup </button>
    <br><br>
    <div v-for="i in sign_up_status" :key="i">
      <div class="item">
        {{ i }}
      </div>
    </div>

    <hr>

    <textarea
      v-model='note_content'
      rows="4" cols="50"
    />
    <br><br>

    <button @click='create_note'> Create a new note </button>
    <br><br>
    <div v-for="i in create_status" :key="i">
      <div class="item">
        {{ i }}
      </div>
    </div>

    <hr>

    <button @click='list_all_notes_current_user'> View all my notes </button>
    <br><br>

    <div v-for="note in todonotes_logged.notes" :key="note.uuid">
      <div class="item">
        {{ note.content }}
      </div>
    </div>
    <br><br>

    <hr>

    <button @click='list_all_notes_some_user'> View notes for other user </button>
    <br><br>

    <div v-for="note in todonotes_some_user.notes" :key="note.uuid">
      <div class="item">
        {{ note.content }}
      </div>
    </div>
    <br><br>

    <hr>

    <button @click='complete_note'> Mark note as complete </button>
    <br><br>
    <div v-for="i in complete_note_status" :key="i">
      <div class="item">
        {{ i }}
      </div>
    </div>

    <hr>

    <button @click='incomplete_note'> Mark note as incomplete </button>
    <br><br>
    <div v-for="i in incomplete_note_status" :key="i">
      <div class="item">
        {{ i }}
      </div>
    </div>

    <hr>

    <button @click='delete_note'> Delete note </button>
    <br><br>
    <div v-for="i in delete_note_status" :key="i">
      <div class="item">
        {{ i }}
      </div>
    </div>

    <hr>


   </div>
</template>

<script>
import { ref } from 'vue'
  export default {
    setup () {

      //API URLs
      const GENERATOR_BASE_USERS = 'http://localhost:8000/api/users';
      const GENERATOR_BASE_TODO = 'http://localhost:8000/api/todonotes';

      //input variables for binding
      const name = ref('');
      const username = ref('');
      const password = ref('');
      const note_content = ref('');

      //output variables to display
      const todonotes_logged = ref([]);
      const todonotes_some_user = ref([]);
      const login_status = ref([]);
      const sign_up_status = ref([]);
      const delete_note_status = ref([]);
      const complete_note_status = ref([]);
      const incomplete_note_status = ref([]);
      const create_status = ref([]);

      // local variables
    //   let data = [];
    //   let key = '';
      let note = '';

      // -------------------------------------------- login
      async function login() {
        const requestOptions = {
            credentials: "include",
            method: "GET",
        };

        const response = await fetch(`${GENERATOR_BASE_USERS}?username=${username.value}&password=${password.value}`, requestOptions);
        login_status.value = await response.json();
        console.log(login_status.value);
        // key = login_status.value.api_key;
        // console.log(key);
      }



      // signup
      async function signup() {
        var formdata = new FormData();
        formdata.append("name", name.value);
        formdata.append("username", username.value);
        formdata.append("password", password.value);


        const requestOptions = {
            credentials: "include",
          method: "POST",
          body: formdata,
        };
        const response = await fetch(`${GENERATOR_BASE_USERS}`, requestOptions);
        sign_up_status.value = await response.json();
        console.log(sign_up_status);
      }








      // create_note
      async function create_note() {
        var formdata = new FormData();
        formdata.append("content", note_content.value);

        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
          method: 'POST',
          headers: myHeaders,
          body: formdata,
          credentials: "include"
        };
        const response = await fetch(`${GENERATOR_BASE_TODO}`, requestOptions);
        console.log(response);
        create_status.value = await response.json();
        console.log(create_status);

        note = create_status.value.uuid;
      }










      // delete_note
      async function delete_note() {

        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
            credentials: "include",
          method: 'DELETE',
          headers: myHeaders
        };
        const response = await fetch(`${GENERATOR_BASE_TODO}/${note}`, requestOptions);
        delete_note_status.value = await response.json();
        console.log(delete_note_status);
      }







      // complete_note
      async function complete_note() {

        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
            credentials: "include",
          method: 'PUT',
          headers: myHeaders
        };
        console.log(note);
        const response = await fetch(`${GENERATOR_BASE_TODO}/complete/${note}`, requestOptions);
        complete_note_status.value = await response.json();
        console.log(response);

      }









      // incomplete_note
      async function incomplete_note() {
        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
            credentials: "include",
          method: 'PUT',
          headers: myHeaders
        };
        const response = await fetch(`${GENERATOR_BASE_TODO}/incomplete/${note}`, requestOptions);
        incomplete_note_status.value = await response.json();
        console.log(incomplete_note_status);
      }








      // list_all_notes_current_user
      async function list_all_notes_current_user() {
        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
            credentials: "include",
          method: 'GET',
          headers: myHeaders
        };
        const response = await fetch(`${GENERATOR_BASE_TODO}`, requestOptions);
        todonotes_logged.value = await response.json();
        console.log(todonotes_logged.value.notes);
      }










      // list_all_notes_some_user
      async function list_all_notes_some_user() {
        var myHeaders = new Headers();
        // myHeaders.append("Authorization", key);

        const requestOptions = {
            credentials: "include",
          method: 'GET',
          headers: myHeaders
        };
        const response = await fetch(`${GENERATOR_BASE_TODO}/58530b71-b165-4cc2-92e4-f3f69142b299`, requestOptions);
        todonotes_some_user.value = await response.json();
        console.log(todonotes_some_user.value);
      }






      return {
        signup,
        create_note,
        delete_note,
        complete_note,
        incomplete_note,
        list_all_notes_current_user,
        list_all_notes_some_user,
        login,

        username,
        password,
        name,
        note_content,
        todonotes_logged,
        todonotes_some_user,
        login_status,
        sign_up_status,
        delete_note_status,
        complete_note_status,
        incomplete_note_status,
        create_status
      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
