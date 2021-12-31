import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/models/user';
import { UserService } from 'src/app/services/user.service';
import { global } from 'src/app/services/global';

@Component({
  selector: 'app-user-edit',
  templateUrl: './user-edit.component.html',
  styleUrls: ['./user-edit.component.css'],
  providers: [UserService]
})
export class UserEditComponent implements OnInit {
  public page_title:string;
  public user: User;
  public status: string = '';
  public identity:any;
  public token:string;
  public froala_options: Object = {
    placeholderText: 'Edit Your Content Here!',
    charCounterCount: true
  };
  public resetVar:any;
  public afuConfig:any;
  public url:string;
  constructor(
    private _userService: UserService
  ) { 
    this.page_title = 'Ajustes de usuario';
    this.user = new User();
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
    this.user.id = this.identity.sub;
    this.user.name = this.identity.name;
    this.user.surname = this.identity.surname;
    this.user.role = this.identity.role;
    this.user.email = this.identity.email;
    this.user.description = this.identity.description;
    this.user.image = this.identity.image;
    this.url = global.url;
    this.afuConfig = {
        multiple: false,
        formatsAllowed: ".jpg,.png, .gif, .jpeg",
        maxSize: "50",
        uploadAPI:  {
          url: global.url+'user/upload',
          method:"POST",
          headers: {
            "Authorization" : this._userService.getToken()
          },
          responseType: 'json',
          // withCredentials: false
        },
        theme: "attachPin",
        hideProgressBar: false,
        hideResetBtn: true,
        hideSelectBtn: false,
        fileNameIndex: true,
        replaceTexts: {
          selectFileBtn: 'Seleccionar Archivo',
          resetBtn: 'Reset',
          uploadBtn: 'Subir',
          dragNDropBox: 'Drag N Drop',
          attachPinBtn: 'Sube tu avatar de usuario...',
          afterUploadMsg_success: '¡Subido correctamente!',
          afterUploadMsg_error: '¡Fallo en la subida!',
          sizeLimit: 'Tamaño máx'
        }
    }
  }

  ngOnInit(): void {
  }

  onSubmit(form: any){
    //localStorage.setItem
    this._userService.update(this.token, this.user).subscribe(
      response => {
        console.log(response);
        if(response.status == 'success'){
          this.status = response.status;

          // Actualizar usuario en sesión
          if(response.changes.name){
            this.user.name = response.changes.name;
          }
          if(response.changes.surname){
            this.user.surname = response.changes.surname;
          }
          if(response.changes.email){
            this.user.email = response.changes.email;
          }
          if(response.changes.description){
            this.user.description = response.changes.description;
          }
          if(response.changes.image){
            this.user.image = response.changes.image;
          }

          // if(response.changes.name){
          //   this.identity.name = response.changes.name;
          // }
          // if(response.changes.surname){
          //   this.identity.surname = response.changes.surname;
          // }
          // if(response.changes.email){
          //   this.identity.email = response.changes.email;
          // }
          // if(response.changes.description){
          //   this.identity.description = response.changes.description;
          // }
          // if(response.changes.image){
          //   this.identity.image = response.changes.image;
          // }
          this.identity = this.user;
          localStorage.setItem('identity', JSON.stringify(this.identity));
        }
      },
      error => {
        this.status = 'error';
        console.log(<any>error);
      }
    );
  }

  avatarUpload(datos:any){
    console.log(datos.body.image);
    let data = datos.body.image;
    this.user.image = data;
    this.identity.image = data;
  }
  

}
