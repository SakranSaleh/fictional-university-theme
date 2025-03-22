import $ from "jquery"

class MyNotes {
  constructor() {
    this.events()
  }

  events() {
    $("#my-notes").on("click", ".delete-note" ,  this.deleteNote)
    $("#my-notes").on("click", ".edit-note",  this.editNote.bind(this))
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this))
    $(".submit-note").on("click", this.createNote.bind(this))
  }


  // Methods will go here
  editNote(e){    
    var thisNode = $(e.target).parents("li")
    // thisNode.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel')
    // thisNode.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field")
    // thisNode.find(".update-note").addClass("update-note--visible")

    if(thisNode.data("state") == "editable"){
        this.mekeNoteReadonly(thisNode)
    }else{
        this.makeNoteEditable(thisNode)
    }
  }

  makeNoteEditable(thisNode){
    thisNode.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel')
    thisNode.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field")
    thisNode.find(".update-note").addClass("update-note--visible")
    thisNode.data("state", "editable")
  }

  mekeNoteReadonly(thisNode){
    thisNode.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit')
    thisNode.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field")
    thisNode.find(".update-note").removeClass("update-note--visible")
    thisNode.data("state", "cancle")

  }


  deleteNote(e) {
    var thisNode = $(e.target).parents("li")
    
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url+'/wp-json/wp/v2/note/' + thisNode.data('id'),
      type: "DELETE",
      success: response => {
        thisNode.slideUp();
        console.log("Congrats");
        console.log(response);
      },
      error: response => {
        console.log("Sorry");
        console.log(response);
      }
    })
  }



  updateNote(e) {
    var thisNode = $(e.target).parents("li")
    var ourUpdatedPost = {
        'title' : thisNode.find('.note-title-field').val(),
        'content' : thisNode.find('.note-body-field').val()
    }
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url+'/wp-json/wp/v2/note/' + thisNode.data('id'),
      type: "POST",
      data: ourUpdatedPost,
      success: response => {
       this.mekeNoteReadonly(thisNode)
        console.log("Congrats");
        console.log(response);
      },
      error: response => {
        console.log("Sorry");
        console.log(response);
      }
    })
  }


  

  createNote(e) {
    var ourNewPost = {
      "title": $(".new-note-title").val(),
      "content": $(".new-note-body").val(),
      "status": "private"
    }

    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
      },
      url: universityData.root_url + "/wp-json/wp/v2/note/",
      type: "POST",
      data: ourNewPost,
      success: response => {
        $(".new-note-title, .new-note-body").val("")
        $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${response.title.raw}">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea readonly class="note-body-field">${response.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>
          `)
          .prependTo("#my-notes")
          .hide()
          .slideDown()

        // $(`
        //     <li >
        //       hello this is an
        //     </li>
        //     `)
        //     .prependTo("#my-notes")
        //     .hide()
        //     .slideDown()

        console.log("Congrats")
        console.log(response)
      },
      error: response => {
        if(response.responseText == "You have reached your post limit"){
            $('.note-limit-message').addClass('active')
        }
        console.log("Sorry")
        console.log(response)
      }
    })
  }


//   createNote(e) {
//     // var thisNode = $(e.target).parents("li")
//     var ourNewPost = {
//         'title' : $(".new-note-title").val(),
//         'content' : $(".new-note-body").val()
//     }
//     $.ajax({
//       beforeSend: xhr => {
//         xhr.setRequestHeader("X-WP-Nonce", universityData.nonce)
//       },
//       url: universityData.root_url+'/wp-json/wp/v2/note/',
//       type: "POST",
//       data: ourNewPost,
//       success: response => {
//        $('.new-note-title, .new-note-body').val('');
//        $('<li>Imagine real data is here</li>').prependTo('#my-notes').hide().slideUp();
//         console.log("Congrats");
//         console.log(response);
//       },
//       error: response => {
//         console.log("Sorry");
//         console.log(response);
//       }
//     })
//   }

}

export default MyNotes









// import $ from 'jquery';

// class MyNotes{
//     constructor(){
//        this.events();
//     }

//     events(){
//         $('.delete-note').on('click', this.deleteNote)
//     }


//     // deleteNote() {
        
//     //     console.log('universityData.nonce', universityData.nonce);

//     //     $.ajax({
//     //         beforeSend: (xhr) => {
//     //             xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
//     //         },
//     //         url: `${universityData.root_url}/wp-json/wp/v2/note/106`,
//     //         type: 'DELETE',
//     //         contentType: "application/json",
//     //         dataType: "json",
//     //         success: (response) => {
//     //             console.log('Deleted successfully');
//     //             console.log(response);
//     //         },
//     //         error: (response) => {
//     //             console.log('Error deleting note');
//     //             console.log(response.responseJSON); // Log detailed error message
//     //         }
//     //     });
//     // }
    

//     deleteNote(){
//         $.ajax({
//             beforeSend : (xhr)=>{
//                 xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
//             },
//             url: universityData.root_url + '/wp-json/wp/v2/note/106',
//             type: 'DELETE',
//             success: (response)=>{
//                 console.log('Congrate');
//                 console.log(response);  
//             } ,
//             error: (response)=>{
//                 console.log('sorry');
//                 console.log(response);
//             }
//         })
//     }


// }


// export default MyNotes;

