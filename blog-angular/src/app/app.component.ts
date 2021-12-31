import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.service';
import { global } from './services/global';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [UserService]
})
export class AppComponent implements OnInit, DoCheck {
  title = 'Blog de Angular';
  public identity:any;
  public token:string = '';
  public url;

  constructor(
    public _userService: UserService
  ){
    this.loadUser();
    this.url = global.url;
  }

  ngOnInit(): void {
      console.log('WebApp cargada correctamente');
  }

  ngDoCheck(): void {
      this.loadUser();
  }

  loadUser(){
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }
}
